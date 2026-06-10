@extends('layouts.print')

@section('content')
    @php $company = $quotation->company; @endphp

    <div class="header">
        <div class="header-left">
            @if ($settings->logo)
                <img src="{{ $settings->logo_url }}" alt="Logo" class="logo" />
            @endif
            <div>
                <div class="company-name">{{ $settings->company_name ?: 'Company Name' }}</div>
                <div class="meta">
                    @if ($settings->address)
                        {{ $settings->address }}<br>
                    @endif
                    @if ($settings->phone)
                        Phone: {{ $settings->phone }}
                        @if ($settings->phone_2)
                            | {{ $settings->phone_2 }}
                        @endif
                        <br>
                    @endif
                    @if ($settings->email)
                        Email: {{ $settings->email }}<br>
                    @endif
                    @if ($settings->ntn_number)
                        NTN: {{ $settings->ntn_number }}
                    @endif
                </div>
            </div>
        </div>
        <div>
            <div class="doc-title">QUOTATION</div>
            <div class="doc-meta">
                <strong>{{ $quotation->quotation_number }}</strong><br>
                Date: {{ $quotation->quotation_date?->format('d M Y') ?? '-' }}<br>
                Status: {{ $quotation->status_label }}
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Bill To (Company)</div>
        <div class="client-box">
            @if ($company)
                <strong>
                    <a href="{{ route('companies.show', $company) }}">{{ $company->company_name }}</a>
                </strong><br>
                @if ($company->company_code)
                    Code: {{ $company->company_code }}<br>
                @endif
                @if ($company->contact_person)
                    Contact: {{ $company->contact_person }}
                    @if ($company->designation)
                        ({{ $company->designation }})
                    @endif
                    <br>
                @endif
                @if ($company->email)
                    Email: {{ $company->email }}<br>
                @endif
                @if ($company->phone)
                    Phone: {{ $company->phone }}<br>
                @endif
                @if ($company->address_line1)
                    {{ $company->address_line1 }}
                    @if ($company->city)
                        , {{ $company->city }}
                    @endif
                    @if ($company->country)
                        , {{ $company->country }}
                    @endif
                    <br>
                @endif
                @if ($company->tax_number)
                    Tax No: {{ $company->tax_number }}
                @endif
            @else
                -
            @endif
        </div>
    </div>

    <div class="section">
        <div class="section-title">Items</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quotation->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->product_name }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format((float) $item->price, 2) }}</td>
                        <td class="text-right">{{ number_format((float) $item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals">
            <tr><td>Subtotal</td><td class="text-right">{{ number_format((float) $quotation->subtotal, 2) }}</td></tr>
            <tr><td>Tax ({{ number_format((float) $quotation->tax_rate, 2) }}%)</td><td class="text-right">{{ number_format((float) $quotation->tax_amount, 2) }}</td></tr>
            <tr class="grand"><td>Total Amount</td><td class="text-right">{{ number_format((float) $quotation->total_amount, 2) }}</td></tr>
        </table>
        <p style="margin-top:8px;font-size:11px;color:#666;">
            Tax Method: {{ $quotation->include_tax ? 'Inclusive' : 'Exclusive' }}
        </p>
    </div>
@endsection
