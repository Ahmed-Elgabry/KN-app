<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\HelperApi;
use App\Http\Traits\ImageProcessing;
use App\Services\Apis\TaxiRequestService;
use App\Services\Apis\TaxiService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class TaxiRequestController extends Controller
{
    use HelperApi, ImageProcessing;

    protected $taxiRequestService;

    public function __construct(TaxiRequestService $taxiRequestService)
    {
        $this->taxiRequestService = $taxiRequestService;
    }

    public function index()
    {
        try{
            $gifts = $this->taxiRequestService->index();
            return $this->onSuccess(200, 'taxi request', $gifts);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }

    }

    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'taxi_id' => 'required',
                'name' => 'required',
                'phone' => 'required',
                'from' => 'required',
                'from_longitude' => 'required',
                'from_latitude' => 'required',
                'to' => 'required',
                'to_longitude' => 'required',
                'to_latitude' => 'required',
                'price' => 'required',
                'notes' => 'required',
            ], [], [
                'taxi_id' => trans('app.taxi_id'),
                'name' => trans('app.name'),
                'phone' => trans('app.phone'),
                'from' => trans('app.from'),
                'from_longitude' => trans('app.from_longitude'),
                'from_latitude' => trans('app.from_latitude'),
                'to' => trans('app.to'),
                'to_longitude' => trans('app.to_longitude'),
                'to_latitude' => trans('app.to_latitude'),
                'price' => trans('app.price'),
                'notes' => trans('app.notes'),
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $discount = $this->taxiRequestService->store($request);
            return $this->onSuccess(200, 'taxi request added successfully', $discount);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function edit($id)
    {
        $gifts = $this->taxiRequestService->edit($id);
        if (empty($gifts)){
            return $this->onSuccess(201, 'taxi', $gifts);
        }
        return $this->onSuccess(200, 'taxi', $gifts);
    }

    public function update(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'taxi_id' => 'required',
                'name' => 'required',
                'phone' => 'required',
                'from' => 'required',
                'from_longitude' => 'required',
                'from_latitude' => 'required',
                'to' => 'required',
                'to_longitude' => 'required',
                'to_latitude' => 'required',
                'price' => 'required',
                'notes' => 'required',
            ], [], [
                'taxi_id' => trans('app.taxi_id'),
                'name' => trans('app.name'),
                'phone' => trans('app.phone'),
                'from' => trans('app.from'),
                'from_longitude' => trans('app.from_longitude'),
                'from_latitude' => trans('app.from_latitude'),
                'to' => trans('app.to'),
                'to_longitude' => trans('app.to_longitude'),
                'to_latitude' => trans('app.to_latitude'),
                'price' => trans('app.price'),
                'notes' => trans('app.notes'),
            ]);

            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $gift = $this->taxiRequestService->update($request);
            return $this->onSuccess(200, 'taxi', $gift);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $this->taxiRequestService->delete($request);
            return $this->onSuccess(200, 'taxi deleted');
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }
}
