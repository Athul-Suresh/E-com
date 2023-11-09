<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMaster extends Model
{
    use HasFactory;
    protected $table  = 'order_masters';
    protected $guarded = [];
    protected $fillable = [

        'order_number',
        'user_id',
        'delivery_address_id',
        'total_item',
        'grand_total',
        'status',
        'payment',

    ];
    // Payment Type := COD,DEBIT,CREDIT,UPI
    function user(){
          return $this->belongsTo(User::class);
    }

    public function details(){
        return $this->hasMany(OrderDetail::class,'order_id');
    }

}
