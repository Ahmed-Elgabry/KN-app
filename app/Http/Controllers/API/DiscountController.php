<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\HelperApi;
use App\Http\Traits\ImageProcessing;
use App\Models\City;
use App\Services\Apis\DiscountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DiscountController extends Controller
{
    use HelperApi, ImageProcessing;

    protected $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->discountService = $discountService;
    }

    public function index()
    {
       return $this->discountService->index();
    }

    public function userDiscounts()
    {
        return $this->onSuccess(200, 'Discounts successfully', $this->discountService->userDiscounts());

    }

    public function cityDiscounts($id)
    {
        return $this->onSuccess(200, 'Discounts successfully', $this->discountService->cityDiscounts($id)); ;
    }

    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'address' => 'required|string',
                'image' => 'required',
                'longitude' => 'nullable',
                'latitude' => 'nullable',
                'description' => 'nullable',
                'discount_percentage' => 'nullable',
                'discount_percentage_text' => 'nullable',
                'daly_discount_text' => 'nullable',
                'discount_type' => 'required',
                'daly_discount' => 'required',
                'all_work_hours' => 'required',
                'from' => 'required',
                'to' => 'required',
                'city_id' => 'required',
            ], [], [
                'name' => trans('app.name'),
                'address' => trans('app.address'),
                'image' => trans('app.image'),
                'longitude' => trans('app.longitude'),
                'latitude' => trans('app.latitude'),
                'description' => trans('app.description'),
                'discount_percentage' => trans('app.discount_percentage'),
                'discount_percentage_text' => trans('app.discount_percentage_text'),
                'daly_discount_text' => trans('app.daly_discount_text'),
                'discount_type' => trans('app.discount_type'),
                'daly_discount' => trans('app.daly_discount'),
                'all_work_hours' => trans('app.all_work_hours'),
                'from' => trans('app.from'),
                'to' => trans('app.to'),
                'city_id' => trans('app.city_id'),
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $discount = $this->discountService->store($request);
            return $this->onSuccess(200, 'Discount added successfully', $discount);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function edit($id)
    {
        return $this->discountService->edit($id);
    }

    public function update(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'address' => 'required|string',
                'image' => 'required',
                'longitude' => 'nullable',
                'latitude' => 'nullable',
                'description' => 'nullable',
                'discount_percentage' => 'nullable',
                'discount_percentage_text' => 'nullable',
                'daly_discount_text' => 'nullable',
                'discount_type' => 'required',
                'daly_discount' => 'required',
                'all_work_hours' => 'required',
                'from' => 'required',
                'to' => 'required',
                'id' => 'required',
                'city_id' => 'required',
            ], [], [
                'name' => trans('app.name'),
                'address' => trans('app.address'),
                'image' => trans('app.image'),
                'longitude' => trans('app.longitude'),
                'latitude' => trans('app.latitude'),
                'description' => trans('app.description'),
                'discount_percentage' => trans('app.discount_percentage'),
                'discount_percentage_text' => trans('app.discount_percentage_text'),
                'daly_discount_text' => trans('app.daly_discount_text'),
                'discount_type' => trans('app.discount_type'),
                'daly_discount' => trans('app.daly_discount'),
                'all_work_hours' => trans('app.all_work_hours'),
                'from' => trans('app.from'),
                'to' => trans('app.to'),
                'id' => trans('app.id'),
                'city_id' => trans('app.city_id'),
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $discount = $this->discountService->update($request);
            return $this->onSuccess(200, 'Discount updated successfully', $discount);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $this->discountService->delete($request);
            return $this->onSuccess(200, 'discount deleted');
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }

    }



}
