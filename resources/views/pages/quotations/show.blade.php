@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Quotation Details" />

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ $quotation->quotation_number }}</h3>
                @if ($quotation->company)
                    <a href="{{ route('companies.show', $quotation->company) }}"
                        class="mt-1 inline-block text-sm font-medium text-brand-500 hover:text-brand-600 hover:underline">
                        {{ $quotation->company->company_name }}
                    </a>
                @else
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">-</p>
                @endif
            </div>
            <div class="flex flex-wrap items-center gap-3">
                @if ($quotation->status === 'success')
                    <span class="inline-flex items-center rounded-full bg-success-50 px-3 py-1 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">Success</span>
                @elseif ($quotation->status === 'reject')
                    <span class="inline-flex items-center rounded-full bg-error-50 px-3 py-1 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500">Reject</span>
                @else
                    <span class="inline-flex items-center rounded-full bg-warning-50 px-3 py-1 text-xs font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-500">Pending</span>
                @endif
                <a href="{{ route('quotations.print', $quotation) }}" target="_blank"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                    Print
                </a>
                <a href="{{ route('quotations.edit', $quotation) }}"
                    class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">Edit</a>
                <a href="{{ route('quotations.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">Back</a>
            </div>
        </div>

        <div class="mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 p-5 lg:p-6">
            <h4 class="mb-5 text-base font-semibold text-gray-800 dark:text-white/90 lg:mb-6">Quotation Information</h4>
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                <div>
                    <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">Quotation ID</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $quotation->quotation_number }}</p>
                </div>
                <div>
                    <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">Company</p>
                    @if ($quotation->company)
                        <a href="{{ route('companies.show', $quotation->company) }}"
                            class="text-sm font-medium text-brand-500 hover:text-brand-600 hover:underline">
                            {{ $quotation->company->company_name }}
                        </a>
                    @else
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">-</p>
                    @endif
                </div>
                <div>
                    <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">Status</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $quotation->status_label }}</p>
                </div>
                <div>
                    <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">Tax Selection</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $quotation->include_tax ? 'Tax Inclusive' : 'Tax Exclusive' }}</p>
                </div>
                <div>
                    <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">Tax Rate</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ number_format((float) $quotation->tax_rate, 2) }}%</p>
                </div>
                <div>
                    <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">Quotation Date</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $quotation->quotation_date?->format('d M Y') ?? '-' }}</p>
                </div>
                <div>
                    <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">Created Date</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $quotation->created_at->format('d M Y, h:i A') }}</p>
                </div>
                @if ($quotation->invoice)
                    <div>
                        <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">Linked Invoice</p>
                        <a href="{{ route('invoices.show', $quotation->invoice) }}"
                            class="text-sm font-medium text-brand-500 hover:text-brand-600 hover:underline">
                            {{ $quotation->invoice->invoice_number }}
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 p-5 lg:p-6">
            <h4 class="mb-5 text-base font-semibold text-gray-800 dark:text-white/90 lg:mb-6">Line Items</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-200 text-xs uppercase text-gray-500 dark:border-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3 text-left">#</th>
                            <th class="px-4 py-3 text-left">Product Name</th>
                            <th class="px-4 py-3 text-left">Quantity</th>
                            <th class="px-4 py-3 text-left">Price</th>
                            <th class="px-4 py-3 text-left">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quotation->items as $index => $item)
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-medium text-gray-800 dark:text-white/90">{{ $item->product_name }}</td>
                                <td class="px-4 py-3">{{ $item->quantity }}</td>
                                <td class="px-4 py-3">{{ number_format((float) $item->price, 2) }}</td>
                                <td class="px-4 py-3">{{ number_format((float) $item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="border border-gray-200 rounded-2xl dark:border-gray-800 p-5 lg:p-6">
            <h4 class="mb-5 text-base font-semibold text-gray-800 dark:text-white/90 lg:mb-6">Amount Summary</h4>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">Subtotal</p>
                    <p class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ number_format((float) $quotation->subtotal, 2) }}</p>
                </div>
                <div>
                    <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">Tax Amount</p>
                    <p class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ number_format((float) $quotation->tax_amount, 2) }}</p>
                </div>
                <div>
                    <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">Total Amount</p>
                    <p class="text-xl font-bold text-brand-500">{{ number_format((float) $quotation->total_amount, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
