<?php


namespace App\Repositories;



use App\Models\PostLikes;

class PostLikeRepository extends BaseRepository
{
    protected $modeler = PostLikes::class;

}
