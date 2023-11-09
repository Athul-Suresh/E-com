<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'v' => $this->id,
            'name'=>$this->name,
            'phone'=>$this->phone_1,
            'pincode'=>$this->pincode,
            'locality'=>$this->locality,
            'address'=>$this->address,
            'city'=>$this->city,
            // 'user'=>$this->user->id,
            'state'=>new StateResource($this->state),
            'landmark'=>$this->landmark,
            'alternative_phone'=>$this->phone_2,
            'status' => $this->status, // if status is 1 active address other wise no deleted by user
            'address_type'=>$this->address_type, // 1-Home , 2-Work
        ];
    }
}
