<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Quotation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InvoiceService
{
    public function generateInvoiceNumber(): string
    {
        $prefix = 'INV-'.date('Y').'-';
        $last = Invoice::where('invoice_number', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->first();

        $next = $last
            ? ((int) substr($last->invoice_number, strlen($prefix))) + 1
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
        return DataTables::of(Invoice::with('company')->select('invoices.*'))
            ->addColumn('company_name', fn (Invoice $invoice) => $invoice->company?->company_name ?? '-')
            ->filterColumn('company_name', function ($query, $keyword) {
                $query->whereHas('company', function ($q) use ($keyword) {
                    $q->where('company_name', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('total_amount', fn (Invoice $invoice) => number_format((float) $invoice->total_amount, 2))
            ->editColumn('tax_amount', fn (Invoice $invoice) => number_format((float) $invoice->tax_amount, 2))
            ->editColumn('invoice_date', fn (Invoice $invoice) => $invoice->invoice_date?->format('d M Y') ?? '-')
            ->addColumn('tax_type', function (Invoice $invoice) {
                if ($invoice->include_tax) {
                    return '<span class="inline-flex items-center rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-600 dark:bg-brand-500/15 dark:text-brand-400">Inclusive</span>';
                }

                return '<span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">Exclusive</span>';
            })
            ->addColumn('status_badge', function (Invoice $invoice) {
                return match ($invoice->status) {
                    'success' => '<span class="inline-flex items-center rounded-full bg-success-50 px-2.5 py-0.5 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">Success</span>',
                    'reject' => '<span class="inline-flex items-center rounded-full bg-error-50 px-2.5 py-0.5 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500">Reject</span>',
                    default => '<span class="inline-flex items-center rounded-full bg-warning-50 px-2.5 py-0.5 text-xs font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-500">Pending</span>',
                };
            })
            ->editColumn('outstanding_amount', fn (Invoice $invoice) => number_format((float) $invoice->outstanding_amount, 2))
            ->addColumn('payment_status_badge', function (Invoice $invoice) {
                if ($invoice->payment_status === 'clear') {
                    return '<span class="inline-flex items-center rounded-full bg-success-50 px-2.5 py-0.5 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">Clear</span>';
                }

                return '<span class="inline-flex items-center rounded-full bg-warning-50 px-2.5 py-0.5 text-xs font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-500">Pending</span>';
            })
            ->addColumn('action', function (Invoice $invoice) {
                return view('pages.invoices.partials.actions', compact('invoice'))->render();
            })
            ->rawColumns(['status_badge', 'payment_status_badge', 'tax_type', 'action'])
            ->make(true);
    }

    public function create(array $data, array $items): Invoice
    {
        return DB::transaction(function () use ($data, $items) {
            $totals = $this->calculateTotals($items, (float) $data['tax_rate'], (bool) $data['include_tax']);

            $invoice = Invoice::create([
                ...$data,
                ...$totals,
                'invoice_number' => $this->generateInvoiceNumber(),
                'outstanding_amount' => $totals['total_amount'],
                'account_receivable' => $totals['total_amount'],
                'payment_status' => 'pending',
            ]);

            $this->syncItems($invoice, $items);

            return $invoice->load(['company', 'items']);
        });
    }

    public function update(Invoice $invoice, array $data, array $items): Invoice
    {
        return DB::transaction(function () use ($invoice, $data, $items) {
            $totals = $this->calculateTotals($items, (float) $data['tax_rate'], (bool) $data['include_tax']);

            $paidTotal = (float) $invoice->payments()->sum('amount');
            $outstanding = max(0, round($totals['total_amount'] - $paidTotal, 2));

            $invoice->update([
                ...$data,
                ...$totals,
                'outstanding_amount' => $outstanding,
                'account_receivable' => $totals['total_amount'],
                'payment_status' => $outstanding <= 0 ? 'clear' : 'pending',
            ]);

            $invoice->items()->delete();
            $this->syncItems($invoice, $items);

            return $invoice->fresh(['company', 'items']);
        });
    }

    public function delete(Invoice $invoice): void
    {
        $invoice->delete();
    }

    public function createFromQuotation(Quotation $quotation): Invoice
    {
        $existing = Invoice::where('quotation_id', $quotation->id)->first();
        if ($existing) {
            return $existing;
        }

        $quotation->load('items');

        return DB::transaction(function () use ($quotation) {
            $invoice = Invoice::create([
                'company_id' => $quotation->company_id,
                'quotation_id' => $quotation->id,
                'invoice_number' => $this->generateInvoiceNumber(),
                'invoice_date' => $quotation->quotation_date ?? now(),
                'subtotal' => $quotation->subtotal,
                'tax_rate' => $quotation->tax_rate,
                'tax_amount' => $quotation->tax_amount,
                'total_amount' => $quotation->total_amount,
                'outstanding_amount' => $quotation->total_amount,
                'account_receivable' => $quotation->total_amount,
                'payment_status' => 'pending',
                'include_tax' => $quotation->include_tax,
                'status' => 'success',
            ]);

            foreach ($quotation->items as $item) {
                $invoice->items()->create([
                    'product_name' => $item->product_name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total,
                ]);
            }

            return $invoice->load(['company', 'items']);
        });
    }

    protected function syncItems(Invoice $invoice, array $items): void
    {
        foreach ($items as $item) {
            $invoice->items()->create([
                'product_name' => $item['product_name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => round((float) $item['quantity'] * (float) $item['price'], 2),
            ]);
        }
    }
}
