<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\HelperApi;
use App\Http\Traits\ImageProcessing;
use App\Services\Apis\EmploymentService;
use App\Services\Apis\HealthService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class HealthController extends Controller
{
    use HelperApi, ImageProcessing;

    protected $healthService;

    public function __construct(HealthService $healthService)
    {
        $this->healthService = $healthService;
    }
    public function index()
    {
        try{
            $gifts = $this->healthService->index();
            return $this->onSuccess(200, 'health', $gifts);
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
                'address' => 'required',
                'description' => 'required',
                'is_paid' => 'required',
                'image' => 'required',
                'craft' => 'required',
                'specialization' => 'required',
                'is_volunteer' => 'required',
            ], [], [
                'ad_plan_id' => trans('app.ad_plan_id'),
                'city_id' => trans('app.city_id'),
                'name' => trans('app.city_id'),
                'phone' => trans('app.phone'),
                'address' => trans('app.address'),
                'description' => trans('app.description'),
                'is_paid' => trans('app.is_paid'),
                'image' => trans('app.image'),
                'craft' => trans('app.craft'),
                'specialization' => trans('app.specialization'),
                'is_volunteer' => trans('app.is_volunteer'),
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $discount = $this->healthService->store($request);
            return $this->onSuccess(200, 'health added successfully', $discount);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function edit($id)
    {
        $gifts = $this->healthService->edit($id);
        if (empty($gifts)){
            return $this->onSuccess(201, 'health', $gifts);
        }
        return $this->onSuccess(200, 'health', $gifts);
    }

    public function update(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'ad_plan_id' => 'required',
                'name' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'description' => 'required',
                'is_paid' => 'required',
                'image' => 'required',
                'craft' => 'required',
                'specialization' => 'required',
                'is_volunteer' => 'required',
                'id' => 'required',
            ], [], [
                'ad_plan_id' => trans('app.ad_plan_id'),
                'name' => trans('app.city_id'),
                'phone' => trans('app.phone'),
                'address' => trans('app.address'),
                'description' => trans('app.description'),
                'is_paid' => trans('app.is_paid'),
                'image' => trans('app.image'),
                'craft' => trans('app.craft'),
                'specialization' => trans('app.specialization'),
                'is_volunteer' => trans('app.is_volunteer'),
                'id' => trans('app.id'),
            ]);

            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $gift = $this->healthService->update($request);
            return $this->onSuccess(200, 'health', $gift);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $this->healthService->delete($request);
            return $this->onSuccess(200, 'health deleted');
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }
}
