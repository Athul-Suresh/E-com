<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderMasterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
             'v'=>$this->id,
            'user'=>$this->user->name,
            'order_number'=>$this->order_number,
            'total_amount'=>$this->grand_total,
            'order_date'=>$this->created_at,
            'payment'=>$this->payment,
            'status'=>$this->status,
            'orderItems'=>OrderDetailResource::collection($this->details),
            'delivery_address'=>new UserAddressResource($this->deliveryAddress),

        ];
    }
}
