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

    public function comment(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'post_id' => 'required',
                'post_user_id' => 'required',
                'content' => 'required',

            ], [], [
                'post_id' => trans('app.post_id'),
                'post_user_id' => trans('app.post_user_id'),
                'content' => trans('app.content'),
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $comment = $this->postActionsService->comment($request);
            if ($comment){
                return $this->onSuccess(200, 'post comment added successfully', $comment);
            }
            return $this->onSuccess(200, 'post comment not added', $comment);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }
    public function comments($id)
    {
        try{
            $comments = $this->postActionsService->comments($id);
            if ($comments){
                return $this->onSuccess(200, 'post comments successfully', $comments);
            }
            return $this->onSuccess(201, 'post comments ', $comments);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }
    public function replayComment(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'post_id' => 'required',
                'post_user_id' => 'required',
                'content' => 'required',
                'parent_id' => 'required',

            ], [], [
                'post_id' => trans('app.post_id'),
                'post_user_id' => trans('app.post_user_id'),
                'content' => trans('app.content'),
                'parent_id' => trans('app.parent_id'),
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $comment = $this->postActionsService->replayComment($request);
            if ($comment){
                return $this->onSuccess(200, 'post comment added successfully', $comment);
            }
            return $this->onSuccess(200, 'post comment not added', $comment);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function deleteComment(Request $request)
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

            $comment = $this->postActionsService->deleteComment($request);
            if ($comment){
                return $this->onSuccess(200, 'post comment deleted successfully', $comment);
            }
            return $this->onSuccess(200, 'post comment not deleted', $comment);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function likeComment(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'comment_id' => 'required',
            ], [], [
                'comment_id' => trans('app.comment_id'),
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $like = $this->postActionsService->likeComment($request);
            if ($like){
                return $this->onSuccess(200, 'comment Liked successfully', $like);
            }
            return $this->onSuccess(200, 'comment not Liked', $like);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }
}
