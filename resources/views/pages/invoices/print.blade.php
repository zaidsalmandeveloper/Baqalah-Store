@extends('layouts.print')

@section('content')
    @include('pages.partials.print.document', [
        'docType' => 'INVOICE',
        'docNumber' => $invoice->invoice_number,
        'docDate' => $invoice->invoice_date?->format('d M Y') ?? '-',
        'status' => $invoice->status,
        'statusLabel' => $invoice->status_label,
        'settings' => $settings,
        'company' => $invoice->company,
        'items' => $invoice->items,
        'subtotal' => $invoice->subtotal,
        'taxRate' => $invoice->tax_rate,
        'taxAmount' => $invoice->tax_amount,
        'totalAmount' => $invoice->total_amount,
        'includeTax' => $invoice->include_tax,
        'linkedRefLabel' => $invoice->quotation ? 'Quotation' : null,
        'linkedRef' => $invoice->quotation?->quotation_number,
    ])
@endsection
