<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'bank_name',
        'account_number',
        'account_holder',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];
}
