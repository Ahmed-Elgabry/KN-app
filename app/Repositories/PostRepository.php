<?php


namespace App\Repositories;

use App\Models\Post;

class PostRepository extends BaseRepository
{
    protected $modeler = Post::class;
}
