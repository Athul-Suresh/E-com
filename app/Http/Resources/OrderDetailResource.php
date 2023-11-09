<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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
            'quantity'=>$this->product_qty,
            'price'=>$this->sub_total,
            'delivery_status'=>$this->status,

        ];
    }
}
