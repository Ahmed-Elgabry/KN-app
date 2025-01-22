<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostComments extends GlobalModel
{
    use HasFactory;

    protected $guarded = [];

    public function replays()
    {
        return $this->hasMany(PostComments::class, 'parent_id', 'id')->with('likes');

    }

    public function likes()
    {
        return $this->hasMany(PostCommentLikes::class, 'comment_id', 'id');

    }

}
