<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\HelperApi;
use App\Http\Traits\ImageProcessing;
use App\Services\Apis\AdPlanService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class AdPlanController extends Controller
{
    use HelperApi, ImageProcessing;

    protected $adPlanService;

    public function __construct(AdPlanService $adPlanService)
    {
        $this->adPlanService = $adPlanService;
    }
    public function index()
    {
        try{
            $gifts = $this->adPlanService->index();
            return $this->onSuccess(200, 'gits', $gifts);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }

    }

    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'days' => 'required',
                'price' => 'required',
            ], [], [
                'title' => trans('app.title'),
                'days' => trans('app.days'),
                'price' => trans('app.price'),
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $discount = $this->adPlanService->store($request);
            return $this->onSuccess(200, 'gift added successfully', $discount);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function edit($id)
    {
        $gifts = $this->adPlanService->edit($id);
        if (empty($gifts)){
            return $this->onSuccess(201, 'gifts', $gifts);
        }
        return $this->onSuccess(200, 'gifts', $gifts);
    }

    public function update(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'days' => 'required',
                'price' => 'required',
                'id' => 'required',
            ], [], [
                'title' => trans('app.title'),
                'days' => trans('app.days'),
                'price' => trans('app.price'),
                'id' => trans('app.id'),
            ]);

            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $gift = $this->adPlanService->update($request);
            return $this->onSuccess(200, 'Gifts', $gift);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $this->adPlanService->delete($request);
            return $this->onSuccess(200, 'gift deleted');
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }

    }
}
