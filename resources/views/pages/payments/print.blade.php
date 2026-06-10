@extends('layouts.print')

@section('content')
    <div class="document">
        <div class="document-accent"></div>
        <div class="document-body">
            <div class="doc-top">
                <div class="brand-block">
                    @if ($settings->logo)
                        <img src="{{ $settings->logo_url }}" alt="Logo" class="logo" />
                    @else
                        <div class="logo-placeholder">{{ strtoupper(substr($settings->company_name ?: 'B', 0, 1)) }}</div>
                    @endif
                    <div>
                        <div class="brand-name">{{ $settings->company_name ?: 'Company Name' }}</div>
                        <div class="brand-meta">
                            @if ($settings->address)
                                <strong>Address:</strong> {{ $settings->address }}<br>
                            @endif
                            @if ($settings->phone)
                                <strong>Phone:</strong> {{ $settings->phone }}
                                @if ($settings->phone_2) | {{ $settings->phone_2 }} @endif
                                <br>
                            @endif
                            @if ($settings->email)
                                <strong>Email:</strong> {{ $settings->email }}<br>
                            @endif
                            @if ($settings->ntn_number)
                                <strong>NTN:</strong> {{ $settings->ntn_number }}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="doc-badge-wrap">
                    <div class="doc-badge invoice">PAYMENT RECEIPT</div>
                    <div class="doc-number">{{ $payment->payment_number ?: 'RCP-'.$payment->id }}</div>
                    <div class="doc-meta-line">Date: <span>{{ $payment->payment_date?->format('d M Y') ?? '-' }}</span></div>
                    @if ($invoice)
                        <div class="doc-meta-line">Invoice: <span>{{ $invoice->invoice_number }}</span></div>
                    @endif
                    <span class="status-pill success">Paid</span>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-card">
                    <div class="info-card-header">Received From</div>
                    <div class="info-card-body">
                        @if ($company)
                            <div class="name">{{ $company->company_name }}</div>
                            @if ($company->contact_person){{ $company->contact_person }}<br>@endif
                            @if ($company->email){{ $company->email }}<br>@endif
                            @if ($company->phone){{ $company->phone }}<br>@endif
                            @if ($company->address_line1){{ $company->address_line1 }}@endif
                        @else
                            —
                        @endif
                    </div>
                </div>
                <div class="info-card">
                    <div class="info-card-header">Payment Details</div>
                    <div class="info-card-body">
                        <strong>Method:</strong> {{ $payment->payment_method_label }}<br>
                        @if ($payment->bank_account)
                            <strong>Bank Account:</strong> {{ $payment->bank_account }}<br>
                        @endif
                        <strong>Amount Paid:</strong> {{ number_format((float) $payment->amount, 2) }}<br>
                        @if ($invoice)
                            <strong>Invoice Total:</strong> {{ number_format((float) $invoice->total_amount, 2) }}<br>
                            <strong>Outstanding:</strong> {{ number_format((float) $invoice->outstanding_amount, 2) }}
                        @endif
                    </div>
                </div>
            </div>

            <div class="totals-wrap" style="margin-top: 24px;">
                <div class="totals-box">
                    <div class="totals-row grand">
                        <span>Amount Received</span>
                        <span>{{ number_format((float) $payment->amount, 2) }}</span>
                    </div>
                </div>
            </div>

            @if ($payment->receipt_url)
                <div style="margin-top: 28px; padding-top: 20px; border-top: 1px solid var(--gray-200);">
                    <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--gray-500); margin-bottom: 10px;">Attached Proof</div>
                    @if (str_ends_with(strtolower($payment->receipt_image ?? ''), '.pdf'))
                        <p style="color: var(--gray-600);">PDF receipt attached — see uploaded file in system.</p>
                    @else
                        <img src="{{ $payment->receipt_url }}" alt="Payment proof" style="max-width: 280px; max-height: 200px; border-radius: 8px; border: 1px solid var(--gray-200);">
                    @endif
                </div>
            @endif

            <div class="doc-footer">
                <p>This is a computer-generated payment receipt. Thank you for your payment.</p>
            </div>
        </div>
    </div>
@endsection
