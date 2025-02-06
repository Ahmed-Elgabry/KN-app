<?php

namespace App\Models;

class Advertisement extends GlobalModel
{
    protected $table = 'advertisements';
    protected $fillable = [
        'social_user_id',
        'entity_name',
        'location',
        'advertisement_description',
        'advertisement_type',
        'advertisement_percentage',
        'description',
        'daily_advertisement',
        'all_day_advertisement',
        'start_discount',
        'end_discount',
        'start_work',
        'end_work',
        'working_days',
        'start_date',
        'end_date',
        'total_advertisement',
        'status'
    ];

    public function advertisementProducts()
    {
        return $this->hasMany(AdvertisementProduct::class, 'advertisement_id' , 'id');
    }

    public function image()
    {
        return $this->morphMany(Media::class, 'mediaable')->whereIn('type' , ['advertisement_image' , 'advertisement_additional_image'])->latest();
    }
}
