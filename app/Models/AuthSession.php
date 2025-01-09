<?php

namespace App\Models;

class AuthSession extends GlobalModel
{
    protected $table = 'auth_session';
    public $timestamps = true;

    protected $dates = ['created_at','updated_at'];
    protected $fillable = [
        'guard_name',
        'access_token',
        'user_id',
        'ip',
        'user_agent',
        'updated_at'
    ];


    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
