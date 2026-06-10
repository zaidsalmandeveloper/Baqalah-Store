@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Add Invoice" />

    <form action="{{ route('invoices.store') }}" method="POST">
        @csrf
        <x-common.component-card title="Add Invoice" desc="Create a new invoice with line items and tax calculation.">
            @include('pages.invoices.partials.form', [
                'companies' => $companies,
                'invoiceNumber' => $invoiceNumber,
            ])

            <div class="flex items-center gap-3 pt-2">
                <x-ui.button type="submit" variant="primary">Save Invoice</x-ui.button>
                <a href="{{ route('invoices.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-3.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                    Cancel
                </a>
            </div>
        </x-common.component-card>
    </form>
@endsection
