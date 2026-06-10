@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Quotation Listing" />

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-success-200 bg-success-50 px-4 py-3 text-sm text-success-700 dark:border-success-500/30 dark:bg-success-500/10 dark:text-success-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 dark:border-gray-800 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Quotation List</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Click status to update. Success converts to invoice automatically.</p>
            </div>
            <a href="{{ route('quotations.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                Add Quotation
            </a>
        </div>

        <div class="overflow-x-auto p-4 sm:p-6">
            <table id="quotations-table" class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead class="text-xs uppercase text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Quotation ID</th>
                        <th class="px-4 py-3">Company</th>
                        <th class="px-4 py-3">Total Amount</th>
                        <th class="px-4 py-3">Tax</th>
                        <th class="px-4 py-3">Tax Type</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- Status Update Modal --}}
    <div id="quotation-status-modal" class="fixed inset-0 z-99999 hidden items-center justify-center overflow-y-auto p-5">
        <div id="quotation-status-backdrop" class="fixed inset-0 bg-gray-400/50 backdrop-blur-[32px]"></div>
        <div class="relative w-full max-w-md rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-lg dark:border-gray-800 dark:bg-gray-900">
            <button type="button" id="quotation-status-close"
                class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M6 6L18 18M6 18L18 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </button>

            <h3 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white/90">Update Quotation Status</h3>
            <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">
                Quotation: <span id="modal-quotation-number" class="font-medium text-gray-800 dark:text-white/90"></span>
            </p>

            <div class="mb-4">
                <label for="modal-status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status</label>
                <select id="modal-status"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="pending">Pending</option>
                    <option value="success">Success</option>
                    <option value="reject">Reject</option>
                </select>
            </div>

            <div id="success-notice" class="mb-4 hidden rounded-lg border border-brand-200 bg-brand-50 px-4 py-3 text-xs text-brand-700 dark:border-brand-500/30 dark:bg-brand-500/10 dark:text-brand-400">
                Selecting <strong>Success</strong> will automatically create an invoice and redirect you to it.
            </div>

            <p id="modal-error" class="mb-4 hidden text-xs text-error-500"></p>

            <div class="flex items-center gap-3">
                <button type="button" id="quotation-status-save"
                    class="inline-flex flex-1 items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 disabled:cursor-not-allowed disabled:opacity-50">
                    Update Status
                </button>
                <button type="button" id="quotation-status-cancel"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                    Cancel
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            const table = $('#quotations-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('quotations.data') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'quotation_number', name: 'quotation_number' },
                    { data: 'company_name', name: 'company.company_name' },
                    { data: 'total_amount', name: 'total_amount' },
                    { data: 'tax_amount', name: 'tax_amount' },
                    { data: 'tax_type', name: 'include_tax', orderable: false, searchable: false },
                    { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                    { data: 'quotation_date', name: 'quotation_date' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                order: [[0, 'desc']],
            });

            let activeQuotationId = null;
            const modal = $('#quotation-status-modal');
            const statusSelect = $('#modal-status');
            const successNotice = $('#success-notice');
            const errorBox = $('#modal-error');
            const saveBtn = $('#quotation-status-save');

            function openModal(quotationId, quotationNumber, currentStatus) {
                activeQuotationId = quotationId;
                $('#modal-quotation-number').text(quotationNumber);
                statusSelect.val(currentStatus);
                errorBox.addClass('hidden').text('');
                toggleSuccessNotice();
                modal.removeClass('hidden').addClass('flex');
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                modal.addClass('hidden').removeClass('flex');
                activeQuotationId = null;
                errorBox.addClass('hidden').text('');
                document.body.style.overflow = 'unset';
            }

            function toggleSuccessNotice() {
                if (statusSelect.val() === 'success') {
                    successNotice.removeClass('hidden');
                } else {
                    successNotice.addClass('hidden');
                }
            }

            $(document).on('click', '.quotation-status-btn', function() {
                openModal(
                    $(this).data('quotation-id'),
                    $(this).data('quotation-number'),
                    $(this).data('current-status')
                );
            });

            statusSelect.on('change', toggleSuccessNotice);

            $('#quotation-status-close, #quotation-status-cancel, #quotation-status-backdrop').on('click', closeModal);

            saveBtn.on('click', function() {
                if (!activeQuotationId) return;

                saveBtn.prop('disabled', true).text('Updating...');
                errorBox.addClass('hidden').text('');

                $.ajax({
                    url: `/quotations/${activeQuotationId}/status`,
                    method: 'PATCH',
                    data: {
                        status: statusSelect.val(),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.redirect_url) {
                            window.location.href = response.redirect_url;
                            return;
                        }
                        closeModal();
                        table.ajax.reload(null, false);
                        saveBtn.prop('disabled', false).text('Update Status');
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'Failed to update status.';
                        errorBox.removeClass('hidden').text(message);
                        saveBtn.prop('disabled', false).text('Update Status');
                    }
                });
            });
        });
    </script>
@endpush
