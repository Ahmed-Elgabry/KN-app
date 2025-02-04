<?php

namespace App\Models;

class ApprovalWalletRequest extends GlobalModel
{

    protected $table = 'approval_wallet_requests';
    protected $fillable = [ 'user_id', 'user_image', 'id_card_image', 'status' ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
