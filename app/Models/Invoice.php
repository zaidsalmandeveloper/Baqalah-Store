<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'company_id',
        'quotation_id',
        'invoice_number',
        'invoice_date',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total_amount',
        'outstanding_amount',
        'account_receivable',
        'payment_status',
        'include_tax',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'subtotal' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'outstanding_amount' => 'decimal:2',
            'account_receivable' => 'decimal:2',
            'include_tax' => 'boolean',
        ];
    }

    public function payments(): HasMany
    {
        return $this->hasMany(InvoicePayment::class);
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return $this->payment_status === 'clear' ? 'Clear' : 'Pending';
    }

    public function getPaidAmountAttribute(): float
    {
        return max(0, (float) $this->total_amount - (float) $this->outstanding_amount);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'success' => 'Success',
            'reject' => 'Reject',
            default => 'Pending',
        };
    }
}
