<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "v"=> $this->id,
            "name"=> $this->name,
            "slug"=> $this->slug,
            "short_description"=> $this->short_description,
            "thumbnail"=> $this->thumbnail,
            // 'brands'=>BrandResource::collection($this->brands),
            'products'=>$this->name,

        ];
    }
}
