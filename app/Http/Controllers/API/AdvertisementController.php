<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Http\Traits\{ HelperApi , ImageProcessing };
use App\Services\Apis\AdvertisementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdvertisementController extends Controller
{
    use HelperApi, ImageProcessing;

    protected AdvertisementService $advertisementService;

    public function __construct(AdvertisementService $advertisementService)
    {
        $this->advertisementService = $advertisementService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return $this->onSuccess(200, 'Advertisement List', $this->advertisementService->index());
        } catch (\Throwable $th) {
            return $this->onError(500, trans('site.server_error'), $th->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'social_user_id' => 'required|integer|exists:social_users,id',
                'entity_name' => 'required|string|max:255',
                'location' => 'required|string|max:500',
                'advertisement_description' => 'nullable|string',
                'advertisement_type' => 'required|in:direct_product,percentage_all,percentage_specific',
                'advertisement_percentage' => 'required_if:percentage_all,percentage_specific|numeric|min:0|max:100',
                'description' => 'nullable|string',
                'daily_advertisement' => 'boolean',
                'all_day_advertisement' => 'boolean',
                'start_discount' => 'nullable|date_format:H:i',
                'end_discount' => 'nullable|date_format:H:i|after:start_discount',
                'start_work' => 'required|date_format:H:i',
                'end_work' => 'required|date_format:H:i|after:start_work',
                'working_days' => 'required|string|max:400',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after:start_date',
                'total_advertisement' => 'required|numeric|min:0',
                'status' => 'sometimes|in:pending,approved,rejected,blocked',
                'advertisement_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                'advertisement_additional_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg',
            ], [], [
                'social_user_id' => trans('app.user_id'),
                'entity_name' => trans('app.entity_name'),
                'location' => trans('app.location'),
                'advertisement_description' => trans('app.advertisement_description'),
                'advertisement_type' => trans('app.advertisement_type'),
                'advertisement_percentage' => trans('app.percentage'),
                'description' => trans('app.description'),
                'daily_advertisement' => trans('app.daily_advertisement'),
                'all_day_advertisement' => trans('app.all_day_advertisement'),
                'start_discount' => trans('app.start_discount'),
                'end_discount' => trans('app.end_discount'),
                'start_work' => trans('app.start_work'),
                'end_work' => trans('app.end_work'),
                'working_days' => trans('app.working_days'),
                'start_date' => trans('app.start_date'),
                'end_date' => trans('app.end_date'),
                'total_advertisement' => trans('app.total_advertisement'),
                'advertisement_image' => trans('app.advertisement_image'),
                'advertisement_additional_image' => trans('app.advertisement_additional_image'),
            ]);

            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            return $this->onSuccess(201, 'Advertisement Created', $this->advertisementService->store($request));
        } catch (\Throwable $th) {
            return $this->onError(500, trans('site.server_error'), $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Advertisement $advertisement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $advertisement = $this->advertisementService->edit($id);
        if (empty($advertisement)){
            return $this->onSuccess(201, 'advertisement', $advertisement);
        }
        return $this->onSuccess(200, 'advertisement', $advertisement);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'social_user_id' => 'required|integer|exists:social_users,id',
                'entity_name' => 'required|string|max:255',
                'location' => 'required|string|max:500',
                'advertisement_description' => 'nullable|string',
                'advertisement_type' => 'required|in:direct_product,percentage_all,percentage_specific',
                'advertisement_percentage' => 'required_if:percentage_all,percentage_specific|numeric|min:0|max:100',
                'description' => 'nullable|string',
                'daily_advertisement' => 'boolean',
                'all_day_advertisement' => 'boolean',
                'start_discount' => 'nullable|date_format:H:i',
                'end_discount' => 'nullable|date_format:H:i|after:start_discount',
                'start_work' => 'required|date_format:H:i',
                'end_work' => 'required|date_format:H:i|after:start_work',
                'working_days' => 'required|string|max:400',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after:start_date',
                'total_advertisement' => 'required|numeric|min:0',
                'status' => 'sometimes|in:pending,approved,rejected,blocked',
                'advertisement_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                'advertisement_additional_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg',
            ], [], [
                'social_user_id' => trans('app.user_id'),
                'entity_name' => trans('app.entity_name'),
                'location' => trans('app.location'),
                'advertisement_description' => trans('app.advertisement_description'),
                'advertisement_type' => trans('app.advertisement_type'),
                'advertisement_percentage' => trans('app.percentage'),
                'description' => trans('app.description'),
                'daily_advertisement' => trans('app.daily_advertisement'),
                'all_day_advertisement' => trans('app.all_day_advertisement'),
                'start_discount' => trans('app.start_discount'),
                'end_discount' => trans('app.end_discount'),
                'start_work' => trans('app.start_work'),
                'end_work' => trans('app.end_work'),
                'working_days' => trans('app.working_days'),
                'start_date' => trans('app.start_date'),
                'end_date' => trans('app.end_date'),
                'total_advertisement' => trans('app.total_advertisement'),
                'advertisement_image' => trans('app.advertisement_image'),
                'advertisement_additional_image' => trans('app.advertisement_additional_image'),
            ]);

            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            return $this->onSuccess(201, 'Advertisement Updated', $this->advertisementService->update($request , $id));
        } catch (\Throwable $th) {
            return $this->onError(500, trans('site.server_error'), $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($request)
    {
        try {
            $this->advertisementService->destroy($request);
            return $this->onSuccess(200, 'advertisement deleted');
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }
}
