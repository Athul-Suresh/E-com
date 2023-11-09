<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'slug'=>$this->product->slug,
            'name'=>$this->product->name,
            'thumbnail'=>$this->product->thumbnail,

            'quantity'=>$this->quantity,
            'price'=>$this->price,
            'delivery_status'=>$this->delivery_status,

        ];
    }
}
