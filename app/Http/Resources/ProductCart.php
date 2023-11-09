<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCart extends JsonResource
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
            'user'=>$this->user_id,
            'product'=>$this->product,
            'quantity'=>$this->quantity,
        ];
    }
}
