<?php

namespace App\Models;

class WalletRequest extends GlobalModel
{

    protected $table = 'approval_wallet_requests';
    protected $fillable = [ 'social_user_id', 'status' ];

    public function socialUser()
    {
        return $this->belongsTo(User::class, 'social_user_id', 'id');
    }

    public function image()
    {
        return $this->morphMany(Media::class, 'mediaable')->where('type', 'walletRequest')->latest();
    }

}
