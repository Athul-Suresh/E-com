<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',

        'brand_id',
        'unit_id',
        'condition_id',
        'voucher_id',
        'category_id',

        'purchase_price',
        'unit_price',
        'offer_price',

        'stock',

        'discount_type',
        'discount',

        'short_description',
        'long_description',

        'thumbnail',
        'featured',
        'status',
        'refundable',
        'cod',
        'warranty',

        'min_qty',
        'max_qty',

        'meta_description',
        'meta_keyword',
        'meta_title',

    ];



    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;

        // Check if the name has been changed
        if ($this->isDirty('name')) {
            $this->attributes['slug'] = $this->generateUniqueSlug($value);
        }
    }
    public function generateSlug($name){
        return $this->generateUniqueSlug($name);
    }
    protected function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $count = 0;
        while ($this->slugExists($slug)) {
            $count++;
            $slug = Str::slug($name) . '-' . $count;
        }
        return $slug;
    }

    protected function slugExists($slug)
    {
        return static::where('slug', $slug)->where('id', '!=', $this->id)->exists();
    }

     public function brand(){
        return $this->belongsTo(Brand::class);

     }
     public function unit(){
        return $this->belongsTo(Unit::class);

     }
     public function condition(){
        return $this->belongsTo(ProductCondition::class);

     }
     public function voucher(){
        return $this->belongsTo(Voucher::class);

     }

     public function mainProductCategories()
     {
         return $this->belongsToMany(MainProductCategory::class, 'category_product', 'product_id', 'category_id');
     }


     public function gallery()
    {
        return $this->hasMany(ProductGallery::class);
        /*
            $product = Product::find($product_id);
            $gallery_images = $product->gallery;

         */
    }

    public function reviews(){
        return $this->hasMany(ProductReview::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }


}
