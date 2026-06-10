<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'company_name',
        'company_code',
        'website',
        'registration_number',
        'tax_number',
        'contact_person',
        'designation',
        'email',
        'phone',
        'address_line1',
        'city',
        'state',
        'postal_code',
        'country',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status ? 'Active' : 'Inactive';
    }
}
