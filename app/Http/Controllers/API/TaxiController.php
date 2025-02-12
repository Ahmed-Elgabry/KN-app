<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\HelperApi;
use App\Http\Traits\ImageProcessing;
use App\Services\Apis\TaxiService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class TaxiController extends Controller
{
    use HelperApi, ImageProcessing;

    protected $taxiService;

    public function __construct(TaxiService $taxiService)
    {
        $this->taxiService = $taxiService;
    }
    public function index()
    {
        try{
            $gifts = $this->taxiService->index();
            return $this->onSuccess(200, 'taxi', $gifts);
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
                'is_paid' => 'required',

                'car_type' => 'required',
                'phone' => 'required',
                'date' => 'required',
                'is_aired' => 'required',
                'from' => 'required',
                'to' => 'required',
                'places' => 'required',
            ], [], [
                'ad_plan_id' => trans('app.ad_plan_id'),
                'city_id' => trans('app.city_id'),
                'is_paid' => trans('app.is_paid'),

                'car_type' => trans('app.car_type'),
                'phone' => trans('app.phone'),
                'date' => trans('app.date'),
                'is_aired' => trans('app.is_aired'),
                'from' => trans('app.from'),
                'to' => trans('app.to'),
                'places' => trans('app.places'),
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $discount = $this->taxiService->store($request);
            return $this->onSuccess(200, 'taxi added successfully', $discount);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function edit($id)
    {
        $gifts = $this->taxiService->edit($id);
        if (empty($gifts)){
            return $this->onSuccess(201, 'taxi', $gifts);
        }
        return $this->onSuccess(200, 'taxi', $gifts);
    }

    public function update(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'ad_plan_id' => 'required',
                'is_paid' => 'required',

                'car_type' => 'required',
                'phone' => 'required',
                'date' => 'required',
                'is_aired' => 'required',
                'from' => 'required',
                'to' => 'required',
                'places' => 'required',
                'id' => 'required',
            ], [], [
                'ad_plan_id' => trans('app.ad_plan_id'),
                'is_paid' => trans('app.is_paid'),

                'car_type' => trans('app.car_type'),
                'phone' => trans('app.phone'),
                'date' => trans('app.date'),
                'is_aired' => trans('app.is_aired'),
                'from' => trans('app.from'),
                'to' => trans('app.to'),
                'places' => trans('app.places'),
                'id' => trans('app.id'),
            ]);

            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $gift = $this->taxiService->update($request);
            return $this->onSuccess(200, 'taxi', $gift);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $this->taxiService->delete($request);
            return $this->onSuccess(200, 'taxi deleted');
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }
}
