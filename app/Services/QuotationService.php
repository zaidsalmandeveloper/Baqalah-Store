<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Quotation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class QuotationService
{
    public function __construct(
        protected InvoiceService $invoiceService
    ) {}

    public function generateQuotationNumber(): string
    {
        $prefix = 'QT-'.date('Y').'-';
        $last = Quotation::where('quotation_number', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->first();

        $next = $last
            ? ((int) substr($last->quotation_number, strlen($prefix))) + 1
            : 1;

        return $prefix.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    public function calculateTotals(array $items, float $taxRate, bool $includeTax): array
    {
        $lineTotal = collect($items)->sum(function (array $item) {
            return (float) $item['quantity'] * (float) $item['price'];
        });

        if ($includeTax) {
            $totalAmount = round($lineTotal, 2);
            $taxAmount = round($totalAmount - ($totalAmount / (1 + ($taxRate / 100))), 2);
            $subtotal = round($totalAmount - $taxAmount, 2);
        } else {
            $subtotal = round($lineTotal, 2);
            $taxAmount = round($subtotal * ($taxRate / 100), 2);
            $totalAmount = round($subtotal + $taxAmount, 2);
        }

        return [
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
        ];
    }

    public function getDataTable(): JsonResponse
    {
        return DataTables::of(Quotation::with(['company', 'invoice'])->select('quotations.*'))
            ->addColumn('company_name', fn (Quotation $quotation) => $quotation->company?->company_name ?? '-')
            ->filterColumn('company_name', function ($query, $keyword) {
                $query->whereHas('company', function ($q) use ($keyword) {
                    $q->where('company_name', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('total_amount', fn (Quotation $quotation) => number_format((float) $quotation->total_amount, 2))
            ->editColumn('tax_amount', fn (Quotation $quotation) => number_format((float) $quotation->tax_amount, 2))
            ->editColumn('quotation_date', fn (Quotation $quotation) => $quotation->quotation_date?->format('d M Y') ?? '-')
            ->addColumn('tax_type', function (Quotation $quotation) {
                if ($quotation->include_tax) {
                    return '<span class="inline-flex items-center rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-600 dark:bg-brand-500/15 dark:text-brand-400">Inclusive</span>';
                }

                return '<span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">Exclusive</span>';
            })
            ->addColumn('status_badge', function (Quotation $quotation) {
                return view('pages.quotations.partials.status-badge', compact('quotation'))->render();
            })
            ->addColumn('action', function (Quotation $quotation) {
                return view('pages.quotations.partials.actions', compact('quotation'))->render();
            })
            ->rawColumns(['status_badge', 'tax_type', 'action'])
            ->make(true);
    }

    public function create(array $data, array $items): Quotation
    {
        return DB::transaction(function () use ($data, $items) {
            $totals = $this->calculateTotals($items, (float) $data['tax_rate'], (bool) $data['include_tax']);

            $quotation = Quotation::create([
                ...$data,
                ...$totals,
                'quotation_number' => $this->generateQuotationNumber(),
            ]);

            $this->syncItems($quotation, $items);

            return $quotation->load(['company', 'items']);
        });
    }

    public function update(Quotation $quotation, array $data, array $items): Quotation
    {
        return DB::transaction(function () use ($quotation, $data, $items) {
            $totals = $this->calculateTotals($items, (float) $data['tax_rate'], (bool) $data['include_tax']);

            $quotation->update([
                ...$data,
                ...$totals,
            ]);

            $quotation->items()->delete();
            $this->syncItems($quotation, $items);

            return $quotation->fresh(['company', 'items']);
        });
    }

    public function delete(Quotation $quotation): void
    {
        $quotation->delete();
    }

    public function updateStatus(Quotation $quotation, string $status): array
    {
        return DB::transaction(function () use ($quotation, $status) {
            $quotation->update(['status' => $status]);

            $invoice = null;
            $redirectUrl = null;
            $message = 'Quotation status updated successfully.';

            if ($status === 'success') {
                $invoice = $this->invoiceService->createFromQuotation($quotation->fresh(['items']));
                $redirectUrl = route('invoices.show', $invoice);
                $message = 'Quotation marked as Success and converted to invoice.';
            }

            return [
                'quotation' => $quotation->fresh(['invoice']),
                'invoice' => $invoice,
                'redirect_url' => $redirectUrl,
                'message' => $message,
            ];
        });
    }

    protected function syncItems(Quotation $quotation, array $items): void
    {
        foreach ($items as $item) {
            $quotation->items()->create([
                'product_name' => $item['product_name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => round((float) $item['quantity'] * (float) $item['price'], 2),
            ]);
        }
    }
}
