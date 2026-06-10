<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeliveryChallanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'delivery_date' => ['required', 'date'],
            'received_person_name' => ['required', 'string', 'max:255'],
            'received_location' => ['required', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.invoice_item_id' => ['required', 'integer', 'exists:invoice_items,id'],
            'items.*.quantity_delivered' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
