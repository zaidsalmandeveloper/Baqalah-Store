<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'module',
        'action',
        'title',
        'description',
        'link',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getModuleLabelAttribute(): string
    {
        return match ($this->module) {
            'company' => 'Company',
            'quotation' => 'Quotation',
            'invoice' => 'Invoice',
            'payment' => 'Payment',
            'delivery_challan' => 'Delivery Challan',
            'user' => 'User',
            'settings' => 'Settings',
            'auth' => 'Auth',
            default => ucfirst(str_replace('_', ' ', $this->module)),
        };
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'login' => 'Login',
            'logout' => 'Logout',
            'payment_recorded' => 'Payment Recorded',
            'status_changed' => 'Status Changed',
            default => ucfirst(str_replace('_', ' ', $this->action)),
        };
    }

    public function getModuleColorAttribute(): string
    {
        return match ($this->module) {
            'company' => 'bg-blue-500',
            'quotation' => 'bg-purple-500',
            'invoice' => 'bg-orange-500',
            'payment' => 'bg-success-500',
            'delivery_challan' => 'bg-teal-500',
            'user' => 'bg-gray-500',
            'settings' => 'bg-slate-500',
            'auth' => 'bg-brand-500',
            default => 'bg-gray-400',
        };
    }
}
