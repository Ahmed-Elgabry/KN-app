<?php

namespace App\Services\Apis;

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

    public function __construct(PostLikeRepository $postLikeRepository,
                                PostFavoriteRepository $postFavoriteRepository,
                                PostRateRepository $postRateRepository)
    {
        parent::__construct();
        $this->postLikeRepository = $postLikeRepository;
        $this->postFavoriteRepository = $postFavoriteRepository;
        $this->postRateRepository = $postRateRepository;

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

}
