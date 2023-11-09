<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'logo',
        'featured',
        'status',
        'meta_title',
        'meta_description',
        'meta_keyword',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = $this->generateUniqueSlug($value);
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
        return static::where('slug', $slug)->exists();
    }


    public function products(){
        return $this->hasMany(Product::class,'category_id');
    }
    public function brands(){
        return $this->hasMany(Brand::class,'category_id');
    }
    public function parent(){
        return $this->belongsTo(MainProductCategory::class,'parent_id');
    }

}
