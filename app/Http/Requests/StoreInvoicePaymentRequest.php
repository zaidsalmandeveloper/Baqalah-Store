<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoicePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $invoice = $this->route('invoice');
        $maxAmount = $invoice ? (float) $invoice->outstanding_amount : 999999999;

        return [
            'payment_method' => ['required', Rule::in(['online', 'cash'])],
            'bank_account' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:'.$maxAmount],
            'payment_date' => ['required', 'date'],
            'receipt_image' => ['nullable', 'file', 'mimes:jpeg,jpg,png,webp,pdf', 'max:5120'],
        ];
    }
}
