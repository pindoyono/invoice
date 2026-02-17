<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $fillable = [
        'company_name',
        'address',
        'phone',
        'email',
        'website',
        'logo',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'terms_conditions',
        'invoice_prefix',
        'signature',
    ];

    public static function getSettings(): ?self
    {
        return self::first();
    }
}
