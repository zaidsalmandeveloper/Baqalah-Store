@php
    $statusClass = match ($status ?? 'pending') {
        'success' => 'success',
        'reject' => 'reject',
        default => 'pending',
    };
    $badgeClass = ($docType ?? '') === 'INVOICE' ? 'invoice' : '';
@endphp

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
                <div class="doc-badge {{ $badgeClass }}">{{ $docType }}</div>
                <div class="doc-number">{{ $docNumber }}</div>
                <div class="doc-meta-line">Date: <span>{{ $docDate }}</span></div>
                @if (!empty($linkedRef))
                    <div class="doc-meta-line">{{ $linkedRefLabel ?? 'Reference' }}: <span>{{ $linkedRef }}</span></div>
                @endif
                <span class="status-pill {{ $statusClass }}">{{ $statusLabel }}</span>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <div class="info-card-header">From</div>
                <div class="info-card-body">
                    <div class="name">{{ $settings->company_name ?: '—' }}</div>
                    @if ($settings->address){{ $settings->address }}<br>@endif
                    @if ($settings->phone){{ $settings->phone }}@if ($settings->phone_2) / {{ $settings->phone_2 }}@endif<br>@endif
                    @if ($settings->email){{ $settings->email }}<br>@endif
                    @if ($settings->ntn_number)NTN: {{ $settings->ntn_number }}@endif
                </div>
            </div>
            <div class="info-card">
                <div class="info-card-header">Bill To</div>
                <div class="info-card-body">
                    @if ($company)
                        <div class="name">
                            <a href="{{ route('companies.show', $company) }}">{{ $company->company_name }}</a>
                        </div>
                        @if ($company->company_code)Code: {{ $company->company_code }}<br>@endif
                        @if ($company->contact_person)
                            {{ $company->contact_person }}
                            @if ($company->designation) ({{ $company->designation }}) @endif
                            <br>
                        @endif
                        @if ($company->email){{ $company->email }}<br>@endif
                        @if ($company->phone){{ $company->phone }}<br>@endif
                        @if ($company->address_line1)
                            {{ $company->address_line1 }}
                            @if ($company->city), {{ $company->city }}@endif
                            @if ($company->country), {{ $company->country }}@endif
                            <br>
                        @endif
                        @if ($company->tax_number)Tax No: {{ $company->tax_number }}@endif
                    @else
                        —
                    @endif
                </div>
            </div>
        </div>

        <div class="section-title">Line Items</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th>Description</th>
                    <th class="text-right" style="width:70px">Qty</th>
                    <th class="text-right" style="width:90px">Unit Price</th>
                    <th class="text-right" style="width:100px">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="product">{{ $item->product_name }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format((float) $item->price, 2) }}</td>
                        <td class="text-right">{{ number_format((float) $item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary-row">
            <div class="tax-note">
                <strong>Tax Selection: {{ $includeTax ? 'Inclusive' : 'Exclusive' }}</strong>
                @if ($includeTax)
                    Prices include tax. Tax amount of {{ number_format((float) $taxRate, 2) }}% has been extracted from the total.
                @else
                    Tax of {{ number_format((float) $taxRate, 2) }}% is calculated on the subtotal and added to reach the grand total.
                @endif
            </div>
            <div class="totals-box">
                <table>
                    <tr>
                        <td>Subtotal</td>
                        <td class="amount">{{ number_format((float) $subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Tax ({{ number_format((float) $taxRate, 2) }}%)</td>
                        <td class="amount">{{ number_format((float) $taxAmount, 2) }}</td>
                    </tr>
                    <tr class="grand">
                        <td>Total Amount</td>
                        <td class="amount">{{ number_format((float) $totalAmount, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="document-footer">
            <div>Thank you for your business.</div>
            <div class="footer-brand">{{ $settings->company_name }}</div>
            <div>Generated {{ now()->format('d M Y, h:i A') }}</div>
        </div>
    </div>
</div>
