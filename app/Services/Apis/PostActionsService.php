<?php

namespace App\Services\Apis;

use App\Repositories\PostCommentLikeRepository;
use App\Repositories\PostCommentRepository;
use App\Repositories\PostFavoriteRepository;
use App\Repositories\PostLikeRepository;
use App\Repositories\PostRateRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class PostActionsService extends BaseService
{
    protected $postLikeRepository;
    protected $postFavoriteRepository;
    protected $postRateRepository;
    protected $postCommentRepository;
    protected $postCommentLikeRepository;

    public function __construct(PostLikeRepository $postLikeRepository,
                                PostFavoriteRepository $postFavoriteRepository,
                                PostRateRepository $postRateRepository,
                                PostCommentRepository $postCommentRepository,
                                PostCommentLikeRepository $postCommentLikeRepository)
    {
        parent::__construct();
        $this->postLikeRepository = $postLikeRepository;
        $this->postFavoriteRepository = $postFavoriteRepository;
        $this->postRateRepository = $postRateRepository;
        $this->postCommentRepository = $postCommentRepository;
        $this->postCommentLikeRepository = $postCommentLikeRepository;
    }

    public function like($request)
    {
        DB::beginTransaction();
        try {
            $status = false;
            $like = $this->postLikeRepository->getFirstWhere(['user_id' => auth()->id(), 'post_id' => $request->post_id]);
            if ($like){
                $like->delete();
            }else{
                $this->postLikeRepository->store([
                    'user_id' => auth()->id(),
                    'post_id' => $request->post_id,
                    'post_user_id' => $request->post_user_id,
                ]);
                $status = true;
            }
            DB::commit();
            return $status;
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function favorite($request)
    {
        DB::beginTransaction();
        try {
            $status = false;
            $like = $this->postFavoriteRepository->getFirstWhere(['user_id' => auth()->id(), 'post_id' => $request->post_id]);
            if ($like){
                $like->delete();
            }else{
                $this->postFavoriteRepository->store([
                    'user_id' => auth()->id(),
                    'post_id' => $request->post_id,
                    'post_user_id' => $request->post_user_id,
                ]);
                $status = true;
            }
            DB::commit();
            return $status;
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function ratePost($request)
    {
        DB::beginTransaction();
        try {
            $this->postRateRepository->store([
                'user_id' => auth()->id(),
                'post_id' => $request->post_id,
                'post_user_id' => $request->post_user_id,
                'rate' => $request->rate,
            ]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function deleteRate($request)
    {
        DB::beginTransaction();
        try {
            $this->postRateRepository->getFirstWhere(['id' => $request->id])->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function comment($request)
    {
        DB::beginTransaction();
        try {
               $comment = $this->postCommentRepository->store([
                    'user_id' => auth()->id(),
                    'post_id' => $request->post_id,
                    'post_user_id' => $request->post_user_id,
                    'content' => $request->content,
                    'gift_id' => $request->gift_id ?? null,
                ]);
            DB::commit();
            return $comment;
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }
    public function replayComment($request)
    {
        DB::beginTransaction();
        try {
               $comment = $this->postCommentRepository->store([
                    'user_id' => auth()->id(),
                    'post_id' => $request->post_id,
                    'post_user_id' => $request->post_user_id,
                    'content' => $request->content,
                    'parent_id' => $request->parent_id,
                    'gift_id' => $request->gift_id ?? null,
                ]);
            DB::commit();
            return $comment;
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function deleteComment($request)
    {
        DB::beginTransaction();
        try {
            $this->postCommentRepository->getFirstWhere(['id' => $request->id])->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function likeComment($request)
    {
        DB::beginTransaction();
        try {
            $status = false;
            $like = $this->postCommentLikeRepository->getFirstWhere(['user_id' => auth()->id(), 'comment_id' => $request->comment_id]);
            if ($like){
                $like->delete();
            }else{
                $this->postCommentLikeRepository->store([
                    'user_id' => auth()->id(),
                    'comment_id' => $request->comment_id,
                ]);
                $status = true;
            }
            DB::commit();
            return $status;
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }
    public function comments($id)
    {
        return $this->postCommentRepository->comments($id);

    }
}
