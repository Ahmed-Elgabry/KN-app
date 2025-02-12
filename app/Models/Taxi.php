<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taxi extends GlobalModel
{
    use HasFactory;

    protected $guarded = [];

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

    public function places()
    {
        return $this->hasMany(TaxiPlace::class, 'taxi_id', 'id');
    }
}
