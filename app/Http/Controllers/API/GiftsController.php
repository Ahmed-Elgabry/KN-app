<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\HelperApi;
use App\Http\Traits\ImageProcessing;
use App\Services\Apis\GiftsService;
use App\Services\Apis\PostActionsService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class GiftsController extends Controller
{
    use HelperApi, ImageProcessing;

    protected $giftsService;

    public function __construct(GiftsService $giftsService)
    {
        $this->giftsService = $giftsService;
    }
    public function index()
    {
        try{
            $gifts = $this->giftsService->index();
            return $this->onSuccess(200, 'gits', $gifts);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }

    }

    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name_en' => 'required|string',
                'name_ar' => 'required|string',
                'value' => 'required',
                'image' => 'required',
            ], [], [
                'name_en' => trans('app.name_en'),
                'name_ar' => trans('app.name_ar'),
                'value' => trans('app.value'),
                'image' => trans('app.image'),
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $discount = $this->giftsService->store($request);
            return $this->onSuccess(200, 'gift added successfully', $discount);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function edit($id)
    {
        $gifts = $this->giftsService->edit($id);
        if (empty($gifts)){
            return $this->onSuccess(201, 'gifts', $gifts);
        }
        return $this->onSuccess(200, 'gifts', $gifts);
    }

    public function update(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name_en' => 'required|string',
                'name_ar' => 'required|string',
                'value' => 'required',
                'image' => 'required',
                'id' => 'required',
            ], [], [
                'name_en' => trans('app.name_en'),
                'name_ar' => trans('app.name_ar'),
                'value' => trans('app.value'),
                'image' => trans('app.image'),
                'id' => trans('app.id'),
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $gift = $this->giftsService->update($request);
            return $this->onSuccess(200, 'Gifts', $gift);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $this->giftsService->delete($request);
            return $this->onSuccess(200, 'gift deleted');
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }

    }
}
