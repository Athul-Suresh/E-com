<?php

namespace App\Http\Resources;

// use Illuminate\Support\Decimal;

use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Type\Decimal;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


    // private function getThumbnailData()
    // {
    //     if($this->thumbnail!==null){
    //         $logoPath = 'public/uploads/product/thumbnail/'.$this->thumbnail; // Assuming 'logo' is the image filename
    //         $logoContents = Storage::get($logoPath);
    //         $mimeType = Storage::mimeType($logoPath);
    //         $base64Data = base64_encode($logoContents);
    //         return $base64Data;
    //     }
    // }

    public function toArray(Request $request): array
    {
        // new BrandResourcecollection($this->brands);
        return [
            'v'=>$this->id,
            'name' =>$this->name ,
            'slug' =>$this->slug ,
            'brand' => new BrandResource($this->brand),
            'category' => $this->mainProductCategories,
            'unit' =>$this->unit ,
            'condition' =>$this->condition,
            'voucher' =>$this->voucher,
            'purchase_price' => floatval($this->purchase_price),
            'unit_price' =>  floatval($this->unit_price),
            'offer_price' => floatval($this->offer_price),
            'stock' =>$this->stock ,
            'discount_type' =>$this->discount_type ,
            'discount' =>$this->discount ,
            'short_description' =>$this->short_description ,
            'long_description' =>$this->long_description ,
            'thumbnail' => $this->thumbnail,
            'gallery' => $this->gallery,
            'featured' =>$this->featured ,
            'status' =>$this->status ,
            'refundable' =>$this->refundable,
            'cod' =>$this->cod ,
            'warranty' =>$this->warranty ,
            'min_qty' =>$this->min_qty ,
            'max_qty' =>$this->max_qty ,
            'meta_title' =>$this->meta_title ,
            'meta_description' =>$this->meta_description,
            'meta_keyword' =>str_replace(',', ' | ', $this->meta_keyword),
            'rating' => ProductReviewResource::collection($this->reviews),
            // 'created_at' =>$this->created_at ,
            // 'updated_at' =>$this->updated_at
            ];
    }
}
