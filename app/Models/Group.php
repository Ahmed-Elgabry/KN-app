<?php

namespace App\Models;

class Group extends GlobalModel
{
    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(SocialUser::class, 'social_user_id');
    }

    public function members()
    {
        return $this->hasMany(GroupMember::class , 'social_user_id');
    }

    public function groupInterest()
    {
        return $this->belongsTo(GroupInterest::class, 'group_interest_id');
    }

    public function media()
    {
        return $this->morphOne(Media::class , 'mediaable')->whereType('main_group_image');
    }

}