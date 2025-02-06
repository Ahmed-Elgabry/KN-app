<?php

namespace App\Models;

class WalletRequest extends GlobalModel
{

    protected $table = 'wallet_requests';
    protected $fillable = [ 'social_user_id', 'status' ];

    public function socialUser()
    {
        return $this->belongsTo(User::class, 'social_user_id', 'id');
    }

    public function image()
    {
        return $this->morphMany(Media::class, 'mediaable')->whereIn('type' , ['user_image' , 'id_card_image'])->latest();
    }

}
