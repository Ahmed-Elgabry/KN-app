<?php

namespace App\Http\Controllers\API;

use App\Models\group;
use App\Http\Controllers\Controller;
use App\Http\Traits\HelperApi;
use App\Services\Apis\GroupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    use HelperApi;

    protected $groupService;
    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $group = $this->groupService->index();
            return $this->onSuccess(200 , 'group' , $group);
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
                'user_id' => 'required|integer|exists:social_users,id',
                'group_interest_id' => 'required|exists:group_interests,id',
                'group_name' => 'required|string|min:2|max:255',
                'group_status' => 'required|in:public,private',
                'is_paid' => 'required|boolean',
                'balance' => 'required|integer',
                'group_description' => 'required|string',
                'main_group_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            ], [], [
                'user_id' => trans('app.user_id'),
                'group_interest_id' => trans('app.group_interest_id'),
                'group_name' => trans('app.group_name'),
                'group_status' => trans('app.group_status'),
                'is_paid' => trans('app.is_paid'),
                'balance' => trans('app.balance'),
                'group_description' => trans('app.group_description'),
                'main_group_image' => trans('app.main_group_image')
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $group = $this->groupService->store($request);
            return $this->onSuccess(200, 'Group added successfully', $group);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $group = $this->groupService->edit($id);
        if (empty($group)){
            return $this->onSuccess(201, 'group', $group);
        }
        return $this->onSuccess(200, 'group', $group);
    }

    /**
     * Get user groups.
     */
    public function userGroups($id)
    {
        $group = $this->groupService->userGroups($id);
        if (empty($group)){
            return $this->onSuccess(201, 'group', $group);
        }
        return $this->onSuccess(200, 'group', $group);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:social_users,id',
                'group_interest_id' => 'required|exists:group_interests,id',
                'group_name' => 'required|string|min:2|max:255',
                'group_status' => 'required|in:public,private',
                'is_paid' => 'required|boolean',
                'balance' => 'required|integer',
                'group_description' => 'required|string',
                'main_group_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                'id' => 'required|integer',
            ], [], [
                'user_id' => trans('app.user_id'),
                'group_interest_id' => trans('app.group_interest_id'),
                'group_name' => trans('app.group_name'),
                'group_status' => trans('app.group_status'),
                'is_paid' => trans('app.is_paid'),
                'balance' => trans('app.balance'),
                'group_description' => trans('app.group_description'),
                'main_group_image' => trans('app.main_group_image'),
                'id' => trans('app.id')
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $groupService = $this->groupService->update($request);
            return $this->onSuccess(200, 'groupService', $groupService);
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
            $this->groupService->delete($request);
            return $this->onSuccess(200, 'group deleted');
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }
}
