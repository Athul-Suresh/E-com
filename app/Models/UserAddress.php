<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'name',
        'phone_1',
        'pincode',
        'locality',
        'address',
        'city',
        'user_id',
        'state_id',
        'landmark',
        'phone_2',
        'address_type',
        'status'

    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function state(){
        return $this->belongsTo(State::class,'state_id');
    }

}
