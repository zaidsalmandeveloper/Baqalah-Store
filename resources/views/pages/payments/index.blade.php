@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Payment Overview" />

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-success-200 bg-success-50 px-4 py-3 text-sm text-success-700 dark:border-success-500/30 dark:bg-success-500/10 dark:text-success-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6">
        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Companies</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Select a company to view quotations, invoices, and record payments.</p>
    </div>

    @if ($companies->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-12 text-center dark:border-gray-700 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">No active companies found. Add a company first.</p>
            <a href="{{ route('companies.create') }}" class="mt-4 inline-flex items-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                Add Company
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($companies as $summary)
                @php $company = $summary['company']; @endphp
                <a href="{{ route('payments.company', $company) }}"
                    class="group rounded-2xl border border-gray-200 bg-white p-6 transition hover:border-brand-300 hover:shadow-theme-md dark:border-gray-800 dark:bg-white/[0.03] dark:hover:border-brand-500/40">
                    <div class="mb-4 flex items-start justify-between gap-3">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 group-hover:text-brand-600 dark:text-white/90 dark:group-hover:text-brand-400">
                                {{ $company->company_name }}
                            </h4>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $company->company_code ?: 'No code' }}</p>
                        </div>
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </span>
                    </div>

                    <div class="mb-4 rounded-xl bg-gray-50 p-4 dark:bg-gray-900/50">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Outstanding Amount</p>
                        <p class="mt-1 text-2xl font-bold text-error-600 dark:text-error-500">{{ number_format($summary['outstanding'], 2) }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="rounded-lg border border-gray-100 px-3 py-2 dark:border-gray-800">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Invoices</p>
                            <p class="font-semibold text-gray-800 dark:text-white/90">{{ $summary['invoice_count'] }}</p>
                        </div>
                        <div class="rounded-lg border border-gray-100 px-3 py-2 dark:border-gray-800">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Quotations</p>
                            <p class="font-semibold text-gray-800 dark:text-white/90">{{ $summary['quotation_count'] }}</p>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <span class="inline-flex items-center rounded-full bg-success-50 px-2.5 py-0.5 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
                            Success {{ $summary['quotations_success'] }}
                        </span>
                        <span class="inline-flex items-center rounded-full bg-warning-50 px-2.5 py-0.5 text-xs font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-500">
                            On Progress {{ $summary['quotations_pending'] }}
                        </span>
                        <span class="inline-flex items-center rounded-full bg-error-50 px-2.5 py-0.5 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500">
                            Reject {{ $summary['quotations_reject'] }}
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
