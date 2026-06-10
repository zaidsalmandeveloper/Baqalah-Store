@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Delivery Challan Details" />

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-success-200 bg-success-50 px-4 py-3 text-sm text-success-700 dark:border-success-500/30 dark:bg-success-500/10 dark:text-success-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ $challan->challan_number }}</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Invoice:
                    <a href="{{ route('invoices.show', $challan->invoice) }}" class="font-medium text-brand-500 hover:text-brand-600">
                        {{ $challan->invoice?->invoice_number }}
                    </a>
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('delivery-challans.print', $challan) }}" target="_blank"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                    Print
                </a>
                @if ($challan->invoice)
                    <a href="{{ route('invoices.delivery-challans.create', $challan->invoice) }}"
                        class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                        Add Another Delivery
                    </a>
                @endif
                <a href="{{ route('delivery-challans.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                    Back
                </a>
            </div>
        </div>

        <div class="mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 p-5 lg:p-6">
            <h4 class="mb-5 text-base font-semibold text-gray-800 dark:text-white/90">Delivery Information</h4>
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <div>
                    <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">Received Person Name</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $challan->received_person_name }}</p>
                </div>
                <div>
                    <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">Delivery Date</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $challan->delivery_date?->format('d M Y') ?? '-' }}</p>
                </div>
                <div class="lg:col-span-2">
                    <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">Received Location / Address</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $challan->received_location }}</p>
                </div>
                <div>
                    <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">Company</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $challan->company?->company_name ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="border border-gray-200 rounded-2xl dark:border-gray-800 p-5 lg:p-6">
            <h4 class="mb-5 text-base font-semibold text-gray-800 dark:text-white/90">Delivered Items</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-200 text-xs uppercase text-gray-500 dark:border-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3 text-left">#</th>
                            <th class="px-4 py-3 text-left">Product</th>
                            <th class="px-4 py-3 text-left">Ordered</th>
                            <th class="px-4 py-3 text-left">Delivered Now</th>
                            <th class="px-4 py-3 text-left">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($challan->items as $index => $item)
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-medium text-gray-800 dark:text-white/90">{{ $item->product_name }}</td>
                                <td class="px-4 py-3">{{ $item->quantity_ordered }}</td>
                                <td class="px-4 py-3 font-medium text-success-600">{{ $item->quantity_delivered }}</td>
                                <td class="px-4 py-3 font-medium text-warning-600">{{ $item->balance_quantity }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
