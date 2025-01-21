<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\HelperApi;
use App\Http\Traits\ImageProcessing;
use App\Services\Apis\PostActionsService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class PostActionsController extends Controller
{
    use HelperApi, ImageProcessing;

    protected $postActionsService;

    public function __construct(PostActionsService $postActionsService)
    {
        $this->postActionsService = $postActionsService;
    }

    public function like(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'post_id' => 'required',
                'post_user_id' => 'required',

            ], [], [
                'post_id' => trans('app.post_id'),
                'post_user_id' => trans('app.post_user_id'),
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $like = $this->postActionsService->like($request);
            if ($like){
                return $this->onSuccess(200, 'post Liked successfully', $like);
            }
            return $this->onSuccess(200, 'post not Liked', $like);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function favorite(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'post_id' => 'required',
                'post_user_id' => 'required',

            ], [], [
                'post_id' => trans('app.post_id'),
                'post_user_id' => trans('app.post_user_id'),
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $favorite = $this->postActionsService->favorite($request);
            if ($favorite){
                return $this->onSuccess(200, 'post Liked successfully', $favorite);
            }
            return $this->onSuccess(200, 'post not Liked', $favorite);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function ratePost(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'post_id' => 'required',
                'post_user_id' => 'required',
                'rate' => 'required',

            ], [], [
                'post_id' => trans('app.post_id'),
                'post_user_id' => trans('app.post_user_id'),
                'rate' => trans('app.rate'),
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $favorite = $this->postActionsService->ratePost($request);
            if ($favorite){
                return $this->onSuccess(200, 'post rated successfully', $favorite);
            }
            return $this->onSuccess(200, 'post not rated', $favorite);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function deleteRate(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [], [
                'id' => trans('app.id'),
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $favorite = $this->postActionsService->deleteRate($request);
            if ($favorite){
                return $this->onSuccess(200, 'post rate deleted successfully', $favorite);
            }
            return $this->onSuccess(200, 'post rae not deleted', $favorite);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }
}
