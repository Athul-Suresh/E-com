<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table='orders';

    protected $fillable=
    [
        'user_id',
        'order_number',
        'payment',
        'delivery_address_id',
        'total_amount',
        'order_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class);
    }

    public function deliveryAddress()
    {
        return $this->belongsTo(UserAddress::class);
    }

}
