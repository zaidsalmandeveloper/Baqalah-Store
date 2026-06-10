<?php

namespace App\Services;

use App\Models\Company;
use App\Models\DeliveryChallan;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Quotation;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class DashboardService
{
    public function getStats(): array
    {
        $hasInvoices = Schema::hasTable('invoices');
        $hasPayments = Schema::hasTable('invoice_payments');
        $hasOutstanding = $hasInvoices && Schema::hasColumn('invoices', 'outstanding_amount');

        $totalInvoiceAmount = $hasInvoices ? (float) Invoice::sum('total_amount') : 0;
        $totalOutstanding = $hasOutstanding ? (float) Invoice::sum('outstanding_amount') : 0;
        $totalReceived = $hasPayments
            ? (float) InvoicePayment::sum('amount')
            : max(0, $totalInvoiceAmount - $totalOutstanding);

        return [
            'companies' => Schema::hasTable('companies')
                ? Company::where('status', true)->count()
                : 0,
            'quotations' => $this->quotationStats(),
            'invoices' => $hasInvoices ? Invoice::count() : 0,
            'total_invoice_amount' => $totalInvoiceAmount,
            'total_outstanding' => $totalOutstanding,
            'total_received' => $totalReceived,
            'payments' => $hasPayments ? InvoicePayment::count() : 0,
            'delivery_challans' => Schema::hasTable('delivery_challans')
                ? DeliveryChallan::count()
                : 0,
            'pending_payment_invoices' => $hasInvoices && Schema::hasColumn('invoices', 'payment_status')
                ? Invoice::where('payment_status', 'pending')->count()
                : 0,
            'cleared_payment_invoices' => $hasInvoices && Schema::hasColumn('invoices', 'payment_status')
                ? Invoice::where('payment_status', 'clear')->count()
                : 0,
        ];
    }

    protected function quotationStats(): array
    {
        if (! Schema::hasTable('quotations')) {
            return ['total' => 0, 'success' => 0, 'pending' => 0, 'reject' => 0];
        }

        return [
            'total' => Quotation::count(),
            'success' => Quotation::where('status', 'success')->count(),
            'pending' => Quotation::where('status', 'pending')->count(),
            'reject' => Quotation::where('status', 'reject')->count(),
        ];
    }

    public function getMonthlyInvoiceChart(): array
    {
        if (! Schema::hasTable('invoices')) {
            return ['labels' => [], 'counts' => [], 'amounts' => []];
        }

        $labels = [];
        $counts = [];
        $amounts = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->format('M Y');

            $query = Invoice::whereYear('invoice_date', $month->year)
                ->whereMonth('invoice_date', $month->month);

            $counts[] = (int) $query->count();
            $amounts[] = (float) (clone $query)->sum('total_amount');
        }

        return compact('labels', 'counts', 'amounts');
    }

    public function getMonthlyPaymentChart(): array
    {
        if (! Schema::hasTable('invoice_payments')) {
            return ['labels' => [], 'amounts' => []];
        }

        $labels = [];
        $amounts = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->format('M Y');

            $amounts[] = (float) InvoicePayment::whereYear('payment_date', $month->year)
                ->whereMonth('payment_date', $month->month)
                ->sum('amount');
        }

        return compact('labels', 'amounts');
    }

    public function getRecentInvoices(int $limit = 5): Collection
    {
        if (! Schema::hasTable('invoices')) {
            return collect();
        }

        return Invoice::with('company')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getRecentQuotations(int $limit = 5): Collection
    {
        if (! Schema::hasTable('quotations')) {
            return collect();
        }

        return Quotation::with('company')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getRecentPayments(int $limit = 5): Collection
    {
        if (! Schema::hasTable('invoice_payments')) {
            return collect();
        }

        return InvoicePayment::with(['invoice', 'company'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getTopOutstandingCompanies(int $limit = 5): Collection
    {
        if (! Schema::hasTable('invoices') || ! Schema::hasColumn('invoices', 'outstanding_amount')) {
            return collect();
        }

        return Company::query()
            ->where('status', true)
            ->withSum('invoices as total_outstanding', 'outstanding_amount')
            ->get()
            ->filter(fn ($company) => (float) ($company->total_outstanding ?? 0) > 0)
            ->sortByDesc('total_outstanding')
            ->take($limit)
            ->values();
    }
}
