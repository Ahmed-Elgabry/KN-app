<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\HelperApi;
use App\Http\Traits\ImageProcessing;
use App\Services\Apis\EmploymentService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class EmploymentController extends Controller
{
    use HelperApi, ImageProcessing;

    protected $employmentService;

    public function __construct(EmploymentService $employmentService)
    {
        $this->employmentService = $employmentService;
    }
    public function index()
    {
        try{
            $gifts = $this->employmentService->index();
            return $this->onSuccess(200, 'employments', $gifts);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }

    }

    public function store(Request $request)
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
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $discount = $this->employmentService->store($request);
            return $this->onSuccess(200, 'employment added successfully', $discount);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function edit($id)
    {
        $gifts = $this->employmentService->edit($id);
        if (empty($gifts)){
            return $this->onSuccess(201, 'employment', $gifts);
        }
        return $this->onSuccess(200, 'employment', $gifts);
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

            $gift = $this->employmentService->update($request);
            return $this->onSuccess(200, 'employments', $gift);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $this->employmentService->delete($request);
            return $this->onSuccess(200, 'employment deleted');
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function userEmployment()
    {
        $discounts = $this->employmentService->userEmployment();
        if (empty($discounts)){
            return $this->onSuccess(201, 'user Employment successfully', $discounts);
        }
        return $this->onSuccess(200, 'user Employment successfully', $discounts);

    }

    public function cityEmployment($id)
    {
        $discounts = $this->employmentService->cityEmployment($id);
        if (empty($discounts)){
            return $this->onSuccess(201, 'city Employment successfully', $discounts);
        }
        return $this->onSuccess(200, 'city Employment successfully', $discounts);
    }
}
