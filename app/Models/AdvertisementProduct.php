<?php

namespace App\Models;


class AdvertisementProduct extends GlobalModel
{
    protected $table = 'advertisement_product';
    protected $fillable = ['advertisement_id', 'product_name' , 'original_price' , 'discounted_price'];
    public $timestamps = true;

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class, 'advertisement_id', 'id');
    }
}
