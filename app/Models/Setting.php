<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Setting extends Model
{
    protected $fillable = [
        'company_name',
        'phone',
        'phone_2',
        'email',
        'address',
        'ntn_number',
        'logo',
    ];

    public function getLogoUrlAttribute(): ?string
    {
        if (! $this->logo) {
            return null;
        }

        if (File::exists(public_path($this->logo))) {
            return asset($this->logo);
        }

        // Fallback for logos stored via storage/app/public.
        return asset('storage/'.$this->logo);
    }
}
