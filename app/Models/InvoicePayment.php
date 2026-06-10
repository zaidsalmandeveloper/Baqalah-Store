<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\File;

class InvoicePayment extends Model
{
    protected $fillable = [
        'invoice_id',
        'company_id',
        'payment_method',
        'bank_account',
        'amount',
        'receipt_image',
        'payment_date',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'date',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return $this->payment_method === 'online' ? 'Online' : 'Cash';
    }

    public function getReceiptUrlAttribute(): ?string
    {
        if (! $this->receipt_image) {
            return null;
        }

        if (File::exists(public_path($this->receipt_image))) {
            return asset($this->receipt_image);
        }

        return asset('storage/'.$this->receipt_image);
    }
}
