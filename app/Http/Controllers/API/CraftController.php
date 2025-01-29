<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\HelperApi;
use App\Http\Traits\ImageProcessing;
use App\Services\Apis\CraftService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class CraftController extends Controller
{
    use HelperApi, ImageProcessing;

    protected $craftService;

    public function __construct(CraftService $craftService)
    {
        $this->craftService = $craftService;
    }
    public function index()
    {
        try{
            $gifts = $this->craftService->index();
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
            ], [], [
                'name_en' => trans('app.name_en'),
                'name_ar' => trans('app.name_ar'),
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $discount = $this->craftService->store($request);
            return $this->onSuccess(200, 'gift added successfully', $discount);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function edit($id)
    {
        $gifts = $this->craftService->edit($id);
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
                'id' => 'required',
            ], [], [
                'name_en' => trans('app.name_en'),
                'name_ar' => trans('app.name_ar'),
                'id' => trans('app.id'),
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $gift = $this->craftService->update($request);
            return $this->onSuccess(200, 'Gifts', $gift);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $this->craftService->delete($request);
            return $this->onSuccess(200, 'gift deleted');
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }

    }
}
