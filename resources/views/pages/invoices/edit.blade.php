@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Edit Invoice" />

    <form action="{{ route('invoices.update', $invoice) }}" method="POST">
        @csrf
        @method('PUT')
        <x-common.component-card title="Edit Invoice" desc="Update invoice details and line items.">
            @include('pages.invoices.partials.form', [
                'invoice' => $invoice,
                'companies' => $companies,
            ])

            <div class="flex items-center gap-3 pt-2">
                <x-ui.button type="submit" variant="primary">Update Invoice</x-ui.button>
                <a href="{{ route('invoices.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-3.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                    Cancel
                </a>
            </div>
        </x-common.component-card>
    </form>
@endsection
