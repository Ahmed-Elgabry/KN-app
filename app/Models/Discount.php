<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discount extends GlobalModel
{
    use HasFactory;
    protected $guarded = [];

    public function mainImage()
    {
        return $this->morphOne(Media::class, 'mediaable')->where('type', 'main_image')->latest();
    }

    public function imageList()
    {
        return $this->morphMany(Media::class, 'mediaable')->where('type', 'image_list')->latest();
    }

    public function products()
    {
        return $this->hasMany(DiscountProduct::class, 'discount_id', 'id');
    }

    public function times()
    {
        return $this->hasOne(DiscountTime::class, 'discount_id', 'id');
    }

}
