<?php

namespace App\Services;

use App\Models\DeliveryChallan;
use App\Models\DeliveryChallanItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DeliveryChallanService
{
    public function generateChallanNumber(): string
    {
        $prefix = 'DC-'.date('Y').'-';
        $last = DeliveryChallan::where('challan_number', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->first();

        $next = $last
            ? ((int) substr($last->challan_number, strlen($prefix))) + 1
            : 1;

        return $prefix.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    public function getInvoiceItemsWithDelivery(Invoice $invoice): Collection
    {
        $invoice->load('items');

        return $invoice->items->map(function (InvoiceItem $item) {
            $delivered = (int) $item->deliveryChallanItems()->sum('quantity_delivered');

            return [
                'id' => $item->id,
                'product_name' => $item->product_name,
                'quantity_ordered' => (int) $item->quantity,
                'quantity_delivered' => $delivered,
                'balance_quantity' => max(0, (int) $item->quantity - $delivered),
                'price' => (float) $item->price,
            ];
        });
    }

    public function getDataTable(): \Illuminate\Http\JsonResponse
    {
        return DataTables::of(DeliveryChallan::with(['invoice', 'company'])->select('delivery_challans.*'))
            ->addColumn('invoice_number', fn (DeliveryChallan $challan) => $challan->invoice?->invoice_number ?? '-')
            ->addColumn('company_name', fn (DeliveryChallan $challan) => $challan->company?->company_name ?? '-')
            ->filterColumn('invoice_number', function ($query, $keyword) {
                $query->whereHas('invoice', fn ($q) => $q->where('invoice_number', 'like', "%{$keyword}%"));
            })
            ->filterColumn('company_name', function ($query, $keyword) {
                $query->whereHas('company', fn ($q) => $q->where('company_name', 'like', "%{$keyword}%"));
            })
            ->editColumn('delivery_date', fn (DeliveryChallan $challan) => $challan->delivery_date?->format('d M Y') ?? '-')
            ->addColumn('action', function (DeliveryChallan $challan) {
                return view('pages.delivery-challans.partials.actions', compact('challan'))->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create(Invoice $invoice, array $data, array $items): DeliveryChallan
    {
        return DB::transaction(function () use ($invoice, $data, $items) {
            $challan = DeliveryChallan::create([
                'invoice_id' => $invoice->id,
                'company_id' => $invoice->company_id,
                'challan_number' => $this->generateChallanNumber(),
                'delivery_date' => $data['delivery_date'],
                'received_person_name' => $data['received_person_name'],
                'received_location' => $data['received_location'],
            ]);

            foreach ($items as $itemData) {
                $qty = (int) ($itemData['quantity_delivered'] ?? 0);
                if ($qty <= 0) {
                    continue;
                }

                $invoiceItem = InvoiceItem::where('invoice_id', $invoice->id)
                    ->where('id', $itemData['invoice_item_id'])
                    ->firstOrFail();

                $alreadyDelivered = (int) $invoiceItem->deliveryChallanItems()->sum('quantity_delivered');
                $balance = max(0, (int) $invoiceItem->quantity - $alreadyDelivered);

                if ($qty > $balance) {
                    throw new \InvalidArgumentException(
                        "Delivery quantity for {$invoiceItem->product_name} cannot exceed balance ({$balance})."
                    );
                }

                $challan->items()->create([
                    'invoice_item_id' => $invoiceItem->id,
                    'product_name' => $invoiceItem->product_name,
                    'quantity_ordered' => (int) $invoiceItem->quantity,
                    'quantity_delivered' => $qty,
                    'balance_quantity' => $balance - $qty,
                ]);
            }

            if ($challan->items()->count() === 0) {
                throw new \InvalidArgumentException('At least one item must have a delivery quantity greater than zero.');
            }

            return $challan->load(['items', 'invoice', 'company']);
        });
    }
}
