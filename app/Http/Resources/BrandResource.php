<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
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
                'slug'=>$this->slug,
                'name'=>$this->name,
                'logo'=>$this->logo,
                'featured'=>$this->featured,
                'meta_description'=>$this->meta_description,
                'meta_title'=>$this->meta_title,
                'meta_keyword'=>$this->meta_keyword,
                'status'=>$this->status,
        ];
    }
}
