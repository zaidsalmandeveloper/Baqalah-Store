@extends('layouts.app')

@section('content')
    <script>
        window.dashboardChartData = {
            chart: @json($chart),
            paymentChart: @json($paymentChart),
            stats: @json($stats),
        };
    </script>

    <x-common.page-breadcrumb pageTitle="Dashboard" />

    {{-- Summary metrics --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <p class="text-sm text-gray-500 dark:text-gray-400">Active Companies</p>
            <h4 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">{{ number_format($stats['companies']) }}</h4>
            <a href="{{ route('companies.index') }}" class="mt-2 inline-block text-xs font-medium text-brand-500 hover:text-brand-600">View companies →</a>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Invoices</p>
            <h4 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">{{ number_format($stats['invoices']) }}</h4>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Amount: {{ number_format($stats['total_invoice_amount'], 2) }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <p class="text-sm text-gray-500 dark:text-gray-400">Outstanding Amount</p>
            <h4 class="mt-2 text-2xl font-bold text-error-600 dark:text-error-500">{{ number_format($stats['total_outstanding'], 2) }}</h4>
            <a href="{{ route('payments.index') }}" class="mt-2 inline-block text-xs font-medium text-brand-500 hover:text-brand-600">Collect payments →</a>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <p class="text-sm text-gray-500 dark:text-gray-400">Received Payments</p>
            <h4 class="mt-2 text-2xl font-bold text-success-600 dark:text-success-500">{{ number_format($stats['total_received'], 2) }}</h4>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ number_format($stats['payments']) }} payment records</p>
        </div>
    </div>

    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 md:gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">Quotations</p>
            <h4 class="mt-2 text-xl font-bold text-gray-800 dark:text-white/90">{{ number_format($stats['quotations']['total']) }}</h4>
            <div class="mt-3 flex flex-wrap gap-2">
                <span class="rounded-full bg-success-50 px-2 py-0.5 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">Success {{ $stats['quotations']['success'] }}</span>
                <span class="rounded-full bg-warning-50 px-2 py-0.5 text-xs font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-500">On Progress {{ $stats['quotations']['pending'] }}</span>
                <span class="rounded-full bg-error-50 px-2 py-0.5 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500">Reject {{ $stats['quotations']['reject'] }}</span>
            </div>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">Payment Status</p>
            <div class="mt-3 space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Pending</span>
                    <span class="font-semibold text-warning-600">{{ $stats['pending_payment_invoices'] }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Clear</span>
                    <span class="font-semibold text-success-600">{{ $stats['cleared_payment_invoices'] }}</span>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">Delivery Challans</p>
            <h4 class="mt-2 text-xl font-bold text-gray-800 dark:text-white/90">{{ number_format($stats['delivery_challans']) }}</h4>
            <a href="{{ route('delivery-challans.index') }}" class="mt-2 inline-block text-xs font-medium text-brand-500 hover:text-brand-600">View challans →</a>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">Quick Actions</p>
            <div class="mt-3 flex flex-col gap-2">
                <a href="{{ route('quotations.create') }}" class="text-sm font-medium text-brand-500 hover:text-brand-600">+ Add Quotation</a>
                <a href="{{ route('invoices.create') }}" class="text-sm font-medium text-brand-500 hover:text-brand-600">+ Add Invoice</a>
                <a href="{{ route('companies.create') }}" class="text-sm font-medium text-brand-500 hover:text-brand-600">+ Add Company</a>
            </div>
        </div>
    </div>

    <div class="mb-6 grid grid-cols-12 gap-4 md:gap-6">
        {{-- Invoice amount --}}
        <div class="col-span-12 xl:col-span-8">
            <div class="flex h-full flex-col rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Invoice Amount Trend</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Total invoice value per month (last 6 months)</p>
                <div class="relative mt-4 h-[300px] w-full overflow-hidden">
                    <div id="dashboard-invoice-amount-chart" class="absolute inset-0 h-full w-full"></div>
                </div>
            </div>
        </div>

        {{-- Quotation donut --}}
        <div class="col-span-12 xl:col-span-4">
            <div class="flex h-full flex-col rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Quotation Status</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Success, On Progress & Reject</p>
                <div class="relative mt-4 h-[320px] w-full overflow-hidden">
                    <div id="dashboard-quotation-chart" class="absolute inset-0 h-full w-full"></div>
                </div>
            </div>
        </div>

        {{-- Invoice count --}}
        <div class="col-span-12 md:col-span-6">
            <div class="flex h-full flex-col rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Invoice Count</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Number of invoices per month</p>
                <div class="relative mt-4 h-[280px] w-full overflow-hidden">
                    <div id="dashboard-invoice-count-chart" class="absolute inset-0 h-full w-full"></div>
                </div>
            </div>
        </div>

        {{-- Payments received --}}
        <div class="col-span-12 md:col-span-6">
            <div class="flex h-full flex-col rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Payments Received</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Monthly collections (last 6 months)</p>
                <div class="relative mt-4 h-[280px] w-full overflow-hidden">
                    <div id="dashboard-payment-chart" class="absolute inset-0 h-full w-full"></div>
                </div>
            </div>
        </div>

        {{-- Payment status --}}
        <div class="col-span-12 md:col-span-6 xl:col-span-4">
            <div class="flex h-full flex-col rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Payment Status</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pending vs cleared invoices</p>
                <div class="relative mt-4 h-[280px] w-full overflow-hidden">
                    <div id="dashboard-payment-status-chart" class="absolute inset-0 h-full w-full"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-6 grid grid-cols-12 gap-4 md:gap-6">
        {{-- Recent invoices --}}
        <div class="col-span-12 xl:col-span-6">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Recent Invoices</h3>
                    <a href="{{ route('invoices.index') }}" class="text-sm font-medium text-brand-500 hover:text-brand-600">View all</a>
                </div>
                <div class="overflow-x-auto p-4 sm:p-6">
                    @if ($recentInvoices->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400">No invoices yet.</p>
                    @else
                        <table class="w-full text-sm text-left">
                            <thead class="border-b border-gray-200 text-xs uppercase text-gray-500 dark:border-gray-700">
                                <tr>
                                    <th class="px-3 py-2">Invoice</th>
                                    <th class="px-3 py-2">Company</th>
                                    <th class="px-3 py-2">Amount</th>
                                    <th class="px-3 py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentInvoices as $invoice)
                                    <tr class="border-b border-gray-100 dark:border-gray-800">
                                        <td class="px-3 py-3">
                                            <a href="{{ route('invoices.show', $invoice) }}" class="font-medium text-brand-500 hover:text-brand-600">{{ $invoice->invoice_number }}</a>
                                        </td>
                                        <td class="px-3 py-3">{{ $invoice->company?->company_name ?? '-' }}</td>
                                        <td class="px-3 py-3">{{ number_format((float) $invoice->total_amount, 2) }}</td>
                                        <td class="px-3 py-3">
                                            @if (($invoice->payment_status ?? 'pending') === 'clear')
                                                <span class="rounded-full bg-success-50 px-2 py-0.5 text-xs font-medium text-success-600">Clear</span>
                                            @else
                                                <span class="rounded-full bg-warning-50 px-2 py-0.5 text-xs font-medium text-warning-600">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        {{-- Recent quotations --}}
        <div class="col-span-12 xl:col-span-6">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Recent Quotations</h3>
                    <a href="{{ route('quotations.index') }}" class="text-sm font-medium text-brand-500 hover:text-brand-600">View all</a>
                </div>
                <div class="overflow-x-auto p-4 sm:p-6">
                    @if ($recentQuotations->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400">No quotations yet.</p>
                    @else
                        <table class="w-full text-sm text-left">
                            <thead class="border-b border-gray-200 text-xs uppercase text-gray-500 dark:border-gray-700">
                                <tr>
                                    <th class="px-3 py-2">Quotation</th>
                                    <th class="px-3 py-2">Company</th>
                                    <th class="px-3 py-2">Amount</th>
                                    <th class="px-3 py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentQuotations as $quotation)
                                    <tr class="border-b border-gray-100 dark:border-gray-800">
                                        <td class="px-3 py-3">
                                            <a href="{{ route('quotations.show', $quotation) }}" class="font-medium text-brand-500 hover:text-brand-600">{{ $quotation->quotation_number }}</a>
                                        </td>
                                        <td class="px-3 py-3">{{ $quotation->company?->company_name ?? '-' }}</td>
                                        <td class="px-3 py-3">{{ number_format((float) $quotation->total_amount, 2) }}</td>
                                        <td class="px-3 py-3">
                                            @include('pages.payments.partials.status-badge', ['status' => $quotation->status])
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-4 md:gap-6">
        {{-- Recent payments --}}
        <div class="col-span-12 xl:col-span-6">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Recent Payments</h3>
                    <a href="{{ route('payments.index') }}" class="text-sm font-medium text-brand-500 hover:text-brand-600">View all</a>
                </div>
                <div class="overflow-x-auto p-4 sm:p-6">
                    @if ($recentPayments->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400">No payments recorded yet.</p>
                    @else
                        <table class="w-full text-sm text-left">
                            <thead class="border-b border-gray-200 text-xs uppercase text-gray-500 dark:border-gray-700">
                                <tr>
                                    <th class="px-3 py-2">Receipt</th>
                                    <th class="px-3 py-2">Company</th>
                                    <th class="px-3 py-2">Amount</th>
                                    <th class="px-3 py-2">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentPayments as $payment)
                                    <tr class="border-b border-gray-100 dark:border-gray-800">
                                        <td class="px-3 py-3 font-medium">{{ $payment->payment_number ?: 'RCP-'.$payment->id }}</td>
                                        <td class="px-3 py-3">{{ $payment->company?->company_name ?? '-' }}</td>
                                        <td class="px-3 py-3 text-success-600">{{ number_format((float) $payment->amount, 2) }}</td>
                                        <td class="px-3 py-3">{{ $payment->payment_date?->format('d M Y') ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        {{-- Top outstanding companies --}}
        <div class="col-span-12 xl:col-span-6">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Top Outstanding Companies</h3>
                    <a href="{{ route('payments.index') }}" class="text-sm font-medium text-brand-500 hover:text-brand-600">Collect →</a>
                </div>
                <div class="overflow-x-auto p-4 sm:p-6">
                    @if ($topOutstanding->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400">No outstanding balances.</p>
                    @else
                        <table class="w-full text-sm text-left">
                            <thead class="border-b border-gray-200 text-xs uppercase text-gray-500 dark:border-gray-700">
                                <tr>
                                    <th class="px-3 py-2">Company</th>
                                    <th class="px-3 py-2 text-right">Outstanding</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topOutstanding as $company)
                                    <tr class="border-b border-gray-100 dark:border-gray-800">
                                        <td class="px-3 py-3">
                                            <a href="{{ route('payments.company', $company) }}" class="font-medium text-brand-500 hover:text-brand-600">{{ $company->company_name }}</a>
                                        </td>
                                        <td class="px-3 py-3 text-right font-semibold text-error-600">{{ number_format((float) ($company->total_outstanding ?? 0), 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
