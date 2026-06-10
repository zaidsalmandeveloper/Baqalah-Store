@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Payments - {{ $company->company_name }}" />

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-success-200 bg-success-50 px-4 py-3 text-sm text-success-700 dark:border-success-500/30 dark:bg-success-500/10 dark:text-success-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ $company->company_name }}</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Quotations, invoices, and payment collection for this company.</p>
        </div>
        <a href="{{ route('payments.index') }}"
            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
            Back to Payments
        </a>
    </div>

    {{-- Summary cards --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Total Outstanding</p>
            <p class="mt-2 text-2xl font-bold text-error-600 dark:text-error-500">{{ number_format($outstanding, 2) }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Invoices</p>
            <p class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">{{ $stats['invoice_count'] }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Quotations</p>
            <p class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">{{ $stats['quotation_count'] }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Quotation Status</p>
            <div class="mt-3 flex flex-wrap gap-2">
                <span class="inline-flex rounded-full bg-success-50 px-2.5 py-0.5 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">Success {{ $stats['quotations_success'] }}</span>
                <span class="inline-flex rounded-full bg-warning-50 px-2.5 py-0.5 text-xs font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-500">On Progress {{ $stats['quotations_pending'] }}</span>
                <span class="inline-flex rounded-full bg-error-50 px-2.5 py-0.5 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500">Reject {{ $stats['quotations_reject'] }}</span>
            </div>
        </div>
    </div>

    {{-- Quotations --}}
    <div class="mb-6 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
            <h4 class="text-base font-medium text-gray-800 dark:text-white/90">Quotations</h4>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">All quotations linked to this company.</p>
        </div>
        <div class="overflow-x-auto p-4 sm:p-6">
            @if ($quotations->isEmpty())
                <p class="text-sm text-gray-500 dark:text-gray-400">No quotations found.</p>
            @else
                <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                    <thead class="border-b border-gray-200 text-xs uppercase text-gray-500 dark:border-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Quotation ID</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quotations as $quotation)
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <td class="px-4 py-3 font-medium">{{ $quotation->quotation_number }}</td>
                                <td class="px-4 py-3">{{ $quotation->quotation_date?->format('d M Y') ?? '-' }}</td>
                                <td class="px-4 py-3">{{ number_format((float) $quotation->total_amount, 2) }}</td>
                                <td class="px-4 py-3">
                                    @include('pages.payments.partials.status-badge', ['status' => $quotation->status])
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('quotations.show', $quotation) }}" class="text-brand-500 hover:text-brand-600">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- Invoices --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
            <h4 class="text-base font-medium text-gray-800 dark:text-white/90">Invoices</h4>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Click an invoice to record a payment.</p>
        </div>
        <div class="overflow-x-auto p-4 sm:p-6">
            @if ($invoices->isEmpty())
                <p class="text-sm text-gray-500 dark:text-gray-400">No invoices found.</p>
            @else
                <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                    <thead class="border-b border-gray-200 text-xs uppercase text-gray-500 dark:border-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Invoice ID</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Outstanding</th>
                            <th class="px-4 py-3">Account Receivable</th>
                            <th class="px-4 py-3">Payment Status</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <td class="px-4 py-3 font-medium">{{ $invoice->invoice_number }}</td>
                                <td class="px-4 py-3">{{ $invoice->invoice_date?->format('d M Y') ?? '-' }}</td>
                                <td class="px-4 py-3">{{ number_format((float) $invoice->total_amount, 2) }}</td>
                                <td class="px-4 py-3 font-medium text-error-600 dark:text-error-500">{{ number_format((float) $invoice->outstanding_amount, 2) }}</td>
                                <td class="px-4 py-3">{{ number_format((float) $invoice->account_receivable, 2) }}</td>
                                <td class="px-4 py-3">
                                    @if ($invoice->payment_status === 'clear')
                                        <span class="inline-flex items-center rounded-full bg-success-50 px-2.5 py-0.5 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">Clear</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-warning-50 px-2.5 py-0.5 text-xs font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-500">Pending</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <button type="button"
                                        class="record-payment-btn text-brand-500 hover:text-brand-600 disabled:cursor-not-allowed disabled:opacity-50"
                                        data-invoice-id="{{ $invoice->id }}"
                                        data-invoice-number="{{ $invoice->invoice_number }}"
                                        data-total="{{ number_format((float) $invoice->total_amount, 2, '.', '') }}"
                                        data-outstanding="{{ number_format((float) $invoice->outstanding_amount, 2, '.', '') }}"
                                        data-payment-status="{{ $invoice->payment_status }}"
                                        @if ($invoice->payment_status === 'clear') disabled @endif>
                                        Record Payment
                                    </button>
                                    <a href="{{ route('invoices.show', $invoice) }}" class="ml-3 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- Payment Modal --}}
    <div id="payment-modal" class="fixed inset-0 z-99999 hidden items-center justify-center overflow-y-auto p-5">
        <div id="payment-modal-backdrop" class="fixed inset-0 bg-gray-400/50 backdrop-blur-[32px]"></div>
        <div class="relative w-full max-w-lg rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-lg dark:border-gray-800 dark:bg-gray-900">
            <button type="button" id="payment-modal-close"
                class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M6 6L18 18M6 18L18 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </button>

            <h3 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white/90">Record Payment</h3>
            <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">
                Invoice: <span id="modal-invoice-number" class="font-medium text-gray-800 dark:text-white/90"></span>
            </p>

            <div class="mb-4 grid grid-cols-2 gap-3 rounded-xl bg-gray-50 p-4 text-sm dark:bg-gray-900/50">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Amount</p>
                    <p id="modal-total-amount" class="font-semibold text-gray-800 dark:text-white/90"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Outstanding</p>
                    <p id="modal-outstanding-amount" class="font-semibold text-error-600 dark:text-error-500"></p>
                </div>
            </div>

            <form id="payment-form" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Bank Account</label>
                    <input type="text" name="bank_account" id="bank_account"
                        placeholder="e.g. HBL - 1234567890"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                </div>

                <div class="mb-4">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Payment Method <span class="text-error-500">*</span></label>
                    <div class="flex gap-4">
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                            <input type="radio" name="payment_method" value="online" class="text-brand-500 focus:ring-brand-500" checked>
                            Online
                        </label>
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                            <input type="radio" name="payment_method" value="cash" class="text-brand-500 focus:ring-brand-500">
                            Cash
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="amount" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Amount <span class="text-error-500">*</span></label>
                    <input type="number" name="amount" id="amount" step="0.01" min="0.01" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                </div>

                <div class="mb-4">
                    <label for="payment_date" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Payment Date</label>
                    <input type="date" name="payment_date" id="payment_date" value="{{ date('Y-m-d') }}"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                </div>

                <div class="mb-4">
                    <label for="receipt_image" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Receipt Picture <span class="text-xs text-gray-400">(optional)</span></label>
                    <input type="file" name="receipt_image" id="receipt_image" accept="image/*,.pdf"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-brand-600 hover:file:bg-brand-100 dark:text-gray-400 dark:file:bg-brand-500/15 dark:file:text-brand-400">
                </div>

                <p id="payment-error" class="mb-4 hidden text-xs text-error-500"></p>

                <div class="flex items-center gap-3">
                    <button type="submit" id="payment-submit"
                        class="inline-flex flex-1 items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 disabled:cursor-not-allowed disabled:opacity-50">
                        Save Payment
                    </button>
                    <button type="button" id="payment-cancel"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('payment-modal');
            const form = document.getElementById('payment-form');
            const errorBox = document.getElementById('payment-error');
            const submitBtn = document.getElementById('payment-submit');
            let activeInvoiceId = null;

            function openModal(btn) {
                activeInvoiceId = btn.dataset.invoiceId;
                form.reset();
                document.getElementById('modal-invoice-number').textContent = btn.dataset.invoiceNumber;
                document.getElementById('modal-total-amount').textContent = parseFloat(btn.dataset.total).toFixed(2);
                document.getElementById('modal-outstanding-amount').textContent = parseFloat(btn.dataset.outstanding).toFixed(2);
                document.getElementById('amount').max = btn.dataset.outstanding;
                document.getElementById('amount').value = btn.dataset.outstanding;
                document.querySelector('input[name="payment_method"][value="online"]').checked = true;
                document.getElementById('payment_date').value = new Date().toISOString().split('T')[0];
                errorBox.classList.add('hidden');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                activeInvoiceId = null;
                form.reset();
            }

            document.querySelectorAll('.record-payment-btn').forEach(btn => {
                btn.addEventListener('click', () => openModal(btn));
            });

            document.getElementById('payment-modal-close').addEventListener('click', closeModal);
            document.getElementById('payment-cancel').addEventListener('click', closeModal);
            document.getElementById('payment-modal-backdrop').addEventListener('click', closeModal);

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                if (!activeInvoiceId) return;

                errorBox.classList.add('hidden');
                submitBtn.disabled = true;

                const formData = new FormData(form);
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                try {
                    const response = await fetch(`{{ url('/payments/invoices') }}/${activeInvoiceId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'Failed to record payment.');
                    }

                    window.location.reload();
                } catch (err) {
                    errorBox.textContent = err.message;
                    errorBox.classList.remove('hidden');
                } finally {
                    submitBtn.disabled = false;
                }
            });
        });
    </script>
@endpush
