<?php

namespace App\Http\Controllers\API;

use App\Models\GroupInterest;
use App\Http\Controllers\Controller;
use App\Http\Traits\HelperApi;
use App\Services\Apis\GroupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    use HelperApi;

    protected $groupInterestService;
    public function __construct(GroupService $groupInterestService)
    {
        $this->groupInterestService = $groupInterestService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $groupInterest = $this->groupInterestService->index();
            return $this->onSuccess(200 , 'groupInterest' , $groupInterest);
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            return $this->onError(500 , trans('site.server_error') , $th->getMessage());
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
        try{
            $validator = Validator::make($request->all(), [
                'social_user_id' => 'required|integer|exists:social_users,id',
                'group_interest_id' => 'required|exists:group_interests,id',
                'group_name' => 'required|string|min:2|max:255',
                'group_status' => 'required|in:public,private',
                'is_paid' => 'required|boolean',
                'price' => 'required|integer',
                'group_description' => 'required|string',
                'group_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ], [], [
                'social_user_id' => trans('app.social_user_id'),
                'group_interest_id' => trans('app.group_interest_id'),
                'group_name' => trans('app.group_name'),
                'group_status' => trans('app.group_status'),
                'is_paid' => trans('app.is_paid'),
                'price' => trans('app.price'),
                'group_description' => trans('app.group_description'),
                'group_image' => trans('app.group_image')
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $groupInterest = $this->groupInterestService->store($request);
            return $this->onSuccess(200, 'Group interest added successfully', $groupInterest);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(GroupInterest $groupInterest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $groupInterest = $this->groupInterestService->edit($id);
        if (empty($groupInterest)){
            return $this->onSuccess(201, 'groupInterest', $groupInterest);
        }
        return $this->onSuccess(200, 'groupInterest', $groupInterest);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name_en' => 'required|string',
                'name_ar' => 'required|string',
                'status' => 'required|boolean',
                'id' => 'required',
            ], [], [
                'name_en' => trans('app.name_en'),
                'name_ar' => trans('app.name_ar'),
                'status' => trans('app.status'),
                'id' => trans('app.id'),
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $groupInterestService = $this->groupInterestService->update($request);
            return $this->onSuccess(200, 'groupInterestService', $groupInterestService);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($request)
    {
        try {
            $this->groupInterestService->delete($request);
            return $this->onSuccess(200, 'group Interest deleted');
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }
}
