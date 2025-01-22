<?php


namespace App\Repositories;



use App\Models\PostCommentLikes;

class PostCommentLikeRepository extends BaseRepository
{
    protected $modeler = PostCommentLikes::class;

}
