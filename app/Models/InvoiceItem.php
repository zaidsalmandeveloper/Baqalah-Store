<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'product_name',
        'quantity',
        'price',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'price' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function deliveryChallanItems(): HasMany
    {
        return $this->hasMany(DeliveryChallanItem::class);
    }

    public function getDeliveredQuantityAttribute(): int
    {
        return (int) $this->deliveryChallanItems()->sum('quantity_delivered');
    }

    public function getBalanceQuantityAttribute(): int
    {
        return max(0, (int) $this->quantity - $this->delivered_quantity);
    }
}
