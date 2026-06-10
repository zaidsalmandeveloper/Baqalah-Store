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
                            @if ($settings->address)<strong>Address:</strong> {{ $settings->address }}<br>@endif
                            @if ($settings->phone)<strong>Phone:</strong> {{ $settings->phone }}<br>@endif
                            @if ($settings->email)<strong>Email:</strong> {{ $settings->email }}<br>@endif
                        </div>
                    </div>
                </div>
                <div class="doc-badge-wrap">
                    <div class="doc-badge invoice">DELIVERY CHALLAN</div>
                    <div class="doc-number">{{ $challan->challan_number }}</div>
                    <div class="doc-meta-line">Date: <span>{{ $challan->delivery_date?->format('d M Y') ?? '-' }}</span></div>
                    @if ($invoice)
                        <div class="doc-meta-line">Invoice: <span>{{ $invoice->invoice_number }}</span></div>
                    @endif
                </div>
            </div>

            <div class="info-grid">
                <div class="info-card">
                    <div class="info-card-header">Deliver To</div>
                    <div class="info-card-body">
                        @if ($company)
                            <div class="name">{{ $company->company_name }}</div>
                            @if ($company->contact_person){{ $company->contact_person }}<br>@endif
                            @if ($company->phone){{ $company->phone }}<br>@endif
                        @endif
                        <strong>Received By:</strong> {{ $challan->received_person_name }}<br>
                        <strong>Location:</strong> {{ $challan->received_location }}
                    </div>
                </div>
                <div class="info-card">
                    <div class="info-card-header">Delivery Details</div>
                    <div class="info-card-body">
                        <strong>Challan No:</strong> {{ $challan->challan_number }}<br>
                        <strong>Delivery Date:</strong> {{ $challan->delivery_date?->format('d M Y') ?? '-' }}<br>
                        @if ($invoice)
                            <strong>Invoice Ref:</strong> {{ $invoice->invoice_number }}
                        @endif
                    </div>
                </div>
            </div>

            <div class="section-title">Delivered Items</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width:40px">#</th>
                        <th>Product</th>
                        <th class="text-right" style="width:80px">Ordered</th>
                        <th class="text-right" style="width:80px">Delivered</th>
                        <th class="text-right" style="width:80px">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($challan->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="product">{{ $item->product_name }}</td>
                            <td class="text-right">{{ $item->quantity_ordered }}</td>
                            <td class="text-right">{{ $item->quantity_delivered }}</td>
                            <td class="text-right">{{ $item->balance_quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="document-footer" style="margin-top: 40px;">
                <div style="display: flex; justify-content: space-between; margin-top: 30px;">
                    <div style="width: 45%; border-top: 1px solid var(--gray-300); padding-top: 8px; text-align: center;">
                        <div style="font-size: 11px; color: var(--gray-500);">Delivered By</div>
                        <div style="margin-top: 24px; font-weight: 600;">{{ $settings->company_name }}</div>
                    </div>
                    <div style="width: 45%; border-top: 1px solid var(--gray-300); padding-top: 8px; text-align: center;">
                        <div style="font-size: 11px; color: var(--gray-500);">Received By</div>
                        <div style="margin-top: 24px; font-weight: 600;">{{ $challan->received_person_name }}</div>
                    </div>
                </div>
                <div style="margin-top: 24px; text-align: center; color: var(--gray-500); font-size: 11px;">
                    Generated {{ now()->format('d M Y, h:i A') }}
                </div>
            </div>
        </div>
    </div>
@endsection
