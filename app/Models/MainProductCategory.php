<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class MainProductCategory extends Model
{
    use HasFactory;
    protected $table='main_product_categories';
    protected $fillable = [
        'name',
        'slug'
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

    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product', 'category_id', 'product_id');
    }


}
