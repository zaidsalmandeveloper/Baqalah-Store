@extends('layouts.print')

@section('content')
    @include('pages.partials.print.document', [
        'docType' => 'QUOTATION',
        'docNumber' => $quotation->quotation_number,
        'docDate' => $quotation->quotation_date?->format('d M Y') ?? '-',
        'status' => $quotation->status,
        'statusLabel' => $quotation->status_label,
        'settings' => $settings,
        'company' => $quotation->company,
        'items' => $quotation->items,
        'subtotal' => $quotation->subtotal,
        'taxRate' => $quotation->tax_rate,
        'taxAmount' => $quotation->tax_amount,
        'totalAmount' => $quotation->total_amount,
        'includeTax' => $quotation->include_tax,
    ])
@endsection
