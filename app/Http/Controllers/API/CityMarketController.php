<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\HelperApi;
use App\Http\Traits\ImageProcessing;
use App\Services\Apis\CityMarketService;
use App\Services\Apis\EmploymentService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class CityMarketController extends Controller
{
    use HelperApi, ImageProcessing;

    protected $cityMarketService;

    public function __construct(CityMarketService $cityMarketService)
    {
        $this->cityMarketService = $cityMarketService;
    }
    public function index()
    {
        try{
            $gifts = $this->cityMarketService->index();
            return $this->onSuccess(200, 'city Market', $gifts);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }

    }

    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'ad_plan_id' => 'required',
                'city_id' => 'required',
                'name' => 'required',
                'phone' => 'required',
                'price' => 'required',
                'description' => 'required',
                'is_paid' => 'required',
                'images' => 'required',
                'address' => 'required',
                'is_auction' => 'required',
                'item_id' => 'required',
            ], [], [
                'ad_plan_id' => trans('app.ad_plan_id'),
                'city_id' => trans('app.city_id'),
                'name' => trans('app.city_id'),
                'phone' => trans('app.phone'),
                'price' => trans('app.price'),
                'description' => trans('app.description'),
                'is_paid' => trans('app.is_paid'),
                'image' => trans('app.image'),
                'address' => trans('app.address'),
                'is_auction' => trans('app.is_auction'),
                'item_id' => trans('app.item_id'),
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $discount = $this->cityMarketService->store($request);
            return $this->onSuccess(200, 'city Market added successfully', $discount);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function edit($id)
    {
        $gifts = $this->cityMarketService->edit($id);
        if (empty($gifts)){
            return $this->onSuccess(201, 'city Market', $gifts);
        }
        return $this->onSuccess(200, 'city Market', $gifts);
    }

    public function update(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'craft_id' => 'required',
                'ad_plan_id' => 'required',
                'city_id' => 'required',
                'name' => 'required',
                'phone' => 'required',
                'price' => 'required',
                'description' => 'required',
                'is_paid' => 'required',
                'image' => 'required',
                'id' => 'required',
            ], [], [
                'craft_id' => trans('app.craft_id'),
                'ad_plan_id' => trans('app.ad_plan_id'),
                'city_id' => trans('app.city_id'),
                'name' => trans('app.city_id'),
                'phone' => trans('app.phone'),
                'price' => trans('app.price'),
                'description' => trans('app.description'),
                'is_paid' => trans('app.is_paid'),
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

            $gift = $this->cityMarketService->update($request);
            return $this->onSuccess(200, 'city Market', $gift);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $this->cityMarketService->delete($request);
            return $this->onSuccess(200, 'city Market deleted');
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }
}
