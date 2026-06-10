<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PaymentService
{
    public function getCompanySummaries(): Collection
    {
        return Company::query()
            ->where('status', true)
            ->withCount([
                'invoices',
                'quotations',
                'quotations as quotations_success_count' => fn ($q) => $q->where('status', 'success'),
                'quotations as quotations_pending_count' => fn ($q) => $q->where('status', 'pending'),
                'quotations as quotations_reject_count' => fn ($q) => $q->where('status', 'reject'),
            ])
            ->withSum('invoices as total_outstanding', 'outstanding_amount')
            ->orderBy('company_name')
            ->get()
            ->map(function (Company $company) {
                return [
                    'company' => $company,
                    'outstanding' => (float) ($company->total_outstanding ?? 0),
                    'invoice_count' => $company->invoices_count,
                    'quotation_count' => $company->quotations_count,
                    'quotations_success' => $company->quotations_success_count,
                    'quotations_pending' => $company->quotations_pending_count,
                    'quotations_reject' => $company->quotations_reject_count,
                ];
            });
    }

    public function getCompanyDetail(Company $company): array
    {
        $company->loadCount([
            'invoices',
            'quotations',
            'quotations as quotations_success_count' => fn ($q) => $q->where('status', 'success'),
            'quotations as quotations_pending_count' => fn ($q) => $q->where('status', 'pending'),
            'quotations as quotations_reject_count' => fn ($q) => $q->where('status', 'reject'),
        ]);

        $quotations = $company->quotations()
            ->latest()
            ->get();

        $invoices = $company->invoices()
            ->withSum('payments as paid_amount', 'amount')
            ->latest()
            ->get();

        return [
            'company' => $company,
            'outstanding' => (float) $invoices->sum('outstanding_amount'),
            'quotations' => $quotations,
            'invoices' => $invoices,
            'stats' => [
                'invoice_count' => $company->invoices_count,
                'quotation_count' => $company->quotations_count,
                'quotations_success' => $company->quotations_success_count,
                'quotations_pending' => $company->quotations_pending_count,
                'quotations_reject' => $company->quotations_reject_count,
            ],
        ];
    }

    public function recordPayment(Invoice $invoice, array $data, ?UploadedFile $receipt = null): InvoicePayment
    {
        return DB::transaction(function () use ($invoice, $data, $receipt) {
            $amount = (float) $data['amount'];

            if ($amount <= 0) {
                throw new \InvalidArgumentException('Payment amount must be greater than zero.');
            }

            if ($amount > (float) $invoice->outstanding_amount) {
                throw new \InvalidArgumentException('Payment amount cannot exceed outstanding amount.');
            }

            $paymentData = [
                'invoice_id' => $invoice->id,
                'company_id' => $invoice->company_id,
                'payment_method' => $data['payment_method'],
                'bank_account' => $data['bank_account'] ?? null,
                'amount' => $amount,
                'payment_date' => $data['payment_date'] ?? now()->toDateString(),
            ];

            if ($receipt) {
                $paymentData['receipt_image'] = $this->storeReceipt($receipt);
            }

            $payment = InvoicePayment::create($paymentData);

            $this->syncInvoicePaymentStatus($invoice->fresh());

            return $payment;
        });
    }

    public function syncInvoicePaymentStatus(Invoice $invoice): void
    {
        $paidTotal = (float) $invoice->payments()->sum('amount');
        $outstanding = max(0, round((float) $invoice->total_amount - $paidTotal, 2));

        $invoice->update([
            'outstanding_amount' => $outstanding,
            'account_receivable' => (float) $invoice->total_amount,
            'payment_status' => $outstanding <= 0 ? 'clear' : 'pending',
        ]);
    }

    protected function storeReceipt(UploadedFile $file): string
    {
        $directory = public_path('uploads/payments');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = time().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
        $file->move($directory, $filename);

        return 'uploads/payments/'.$filename;
    }
}
