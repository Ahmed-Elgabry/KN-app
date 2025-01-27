<?php


namespace App\Repositories;



use App\Models\PostComments;

class PostCommentRepository extends BaseRepository
{
    protected $modeler = PostComments::class;

    public function comments($id)
    {
        return $this->modeler->where('post_id', $id)->where('parent_id', null)
            ->with('replays')
            ->with('likes')
            ->with('gifts')
            ->get();
    }

}
