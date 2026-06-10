@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Delivery Challan Listing" />

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-success-200 bg-success-50 px-4 py-3 text-sm text-success-700 dark:border-success-500/30 dark:bg-success-500/10 dark:text-success-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 dark:border-gray-800 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Delivery Challan List</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">All delivery challans. Create new from an invoice detail page.</p>
            </div>
            <a href="{{ route('invoices.index') }}"
                class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                Go to Invoices
            </a>
        </div>

        <div class="overflow-x-auto p-4 sm:p-6">
            <table id="delivery-challans-table" class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead class="text-xs uppercase text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Challan No.</th>
                        <th class="px-4 py-3">Invoice</th>
                        <th class="px-4 py-3">Company</th>
                        <th class="px-4 py-3">Received By</th>
                        <th class="px-4 py-3">Delivery Date</th>
                        <th class="px-4 py-3">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#delivery-challans-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('delivery-challans.data') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'challan_number', name: 'challan_number' },
                    { data: 'invoice_number', name: 'invoice.invoice_number' },
                    { data: 'company_name', name: 'company.company_name' },
                    { data: 'received_person_name', name: 'received_person_name' },
                    { data: 'delivery_date', name: 'delivery_date' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                order: [[0, 'desc']],
            });
        });
    </script>
@endpush
