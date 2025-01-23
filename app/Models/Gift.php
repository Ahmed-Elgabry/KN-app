<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gift extends GlobalModel
{
    use HasFactory;

    protected $guarded = [];

    public function image()
    {
        return $this->morphOne(Media::class, 'mediaable')->where('type', 'gift')->latest();

    }
}
