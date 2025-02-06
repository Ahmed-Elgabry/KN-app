<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessCenter extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function image()
    {
        return $this->morphOne(Media::class, 'mediaable')->latest();
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
