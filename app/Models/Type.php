<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Type extends GlobalModel
{
    public $table = 'types';
    public $timestamps = false;
    protected $fillable = ['id','name','price','created_at'];
}
