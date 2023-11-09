<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $table = 'vouchers';
    protected $fillable = [
        'name',
        'voucher_code',
        'discount',
        'discount_type',
        'expires_at',
        'usage_limit',
        'status',
        ];
}
