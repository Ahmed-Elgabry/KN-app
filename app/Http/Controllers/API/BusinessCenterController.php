<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\HelperApi;
use App\Http\Traits\ImageProcessing;
use App\Services\Apis\BusinessCenterService;
use App\Services\Apis\EmploymentService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class BusinessCenterController extends Controller
{
    use HelperApi, ImageProcessing;

    protected $businessCenterService;

    public function __construct(BusinessCenterService $businessCenterService)
    {
        $this->businessCenterService = $businessCenterService;
    }
    public function index()
    {
        try{
            $gifts = $this->businessCenterService->index();
            return $this->onSuccess(200, 'business Center', $gifts);
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
                'type' => 'required',
                'work_hours' => 'required',
                'phone' => 'required',
                'price' => 'required',
                'address' => 'required',
                'description' => 'required',
                'is_paid' => 'required',
                'image' => 'required',
            ], [], [
                'ad_plan_id' => trans('app.ad_plan_id'),
                'city_id' => trans('app.city_id'),
                'name' => trans('app.name'),
                'address' => trans('app.address'),
                'type' => trans('app.type'),
                'work_hours' => trans('app.work_hours'),
                'phone' => trans('app.phone'),
                'price' => trans('app.price'),
                'description' => trans('app.description'),
                'is_paid' => trans('app.is_paid'),
                'image' => trans('app.image'),
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $discount = $this->businessCenterService->store($request);
            return $this->onSuccess(200, 'business Center added successfully', $discount);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function edit($id)
    {
        $gifts = $this->businessCenterService->edit($id);
        if (empty($gifts)){
            return $this->onSuccess(201, 'business Center', $gifts);
        }
        return $this->onSuccess(200, 'business Center', $gifts);
    }

    public function update(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'ad_plan_id' => 'required',
                'city_id' => 'required',
                'name' => 'required',
                'type' => 'required',
                'work_hours' => 'required',
                'phone' => 'required',
                'price' => 'required',
                'address' => 'required',
                'description' => 'required',
                'is_paid' => 'required',
                'image' => 'required',
                'id' => 'required',
            ], [], [
                'ad_plan_id' => trans('app.ad_plan_id'),
                'city_id' => trans('app.city_id'),
                'name' => trans('app.name'),
                'address' => trans('app.address'),
                'type' => trans('app.type'),
                'work_hours' => trans('app.work_hours'),
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

            $gift = $this->businessCenterService->update($request);
            return $this->onSuccess(200, 'business Center', $gift);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $this->businessCenterService->delete($request);
            return $this->onSuccess(200, 'business Center deleted');
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function userBusinessCenter()
    {
        $discounts = $this->businessCenterService->userBusinessCenter();
        if (empty($discounts)){
            return $this->onSuccess(201, 'user business Center successfully', $discounts);
        }
        return $this->onSuccess(200, 'user business Center successfully', $discounts);

    }

    public function cityBusinessCenter($id)
    {
        $discounts = $this->businessCenterService->cityBusinessCenter($id);
        if (empty($discounts)){
            return $this->onSuccess(201, 'city business Center successfully', $discounts);
        }
        return $this->onSuccess(200, 'city business Center successfully', $discounts);
    }
}
