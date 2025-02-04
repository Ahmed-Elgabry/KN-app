<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employment extends GlobalModel
{
    use HasFactory;

    protected $guarded = [];

    public function image()
    {
        return $this->morphOne(Media::class, 'mediaable')->where('type', 'main_image')->latest();
    }

    public function craft()
    {
        return $this->belongsTo(Craft::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function adPlan()
    {
        return $this->belongsTo(AdPlans::class, 'ad_plan_id');
    }

    public function user()
    {
        return $this->belongsTo(SocialUser::class, 'user_id');
    }
}
