<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityMarket extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function images()
    {
        return $this->morphMany(Media::class, 'mediaable')->get();
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
