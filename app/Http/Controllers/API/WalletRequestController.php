<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\{ HelperApi , ImageProcessing };
use App\Models\WalletRequest;
use App\Services\Apis\WalletRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WalletRequestController extends Controller
{
    use HelperApi, ImageProcessing;

    protected WalletRequestService $WalletRequestService;

    public function __construct(WalletRequestService $WalletRequestService)
    {
        $this->WalletRequestService = $WalletRequestService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $WalletRequests = $this->WalletRequestService->index();
            return $this->onSuccess(200, 'WalletRequests', $WalletRequests);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{

            $validator = Validator::make($request->all(), [
                'social_user_id' => 'required|int|exists:social_users,id',
                'user_image' => 'required|image:jpeg,png,jpg',
                'id_card_image' => 'required|image:jpeg,png,jpg'
            ], [], [
                'social_user_id' => trans('app.user_id'),
                'user_image' => trans('app.user_image'),
                'id_card_image' => trans('app.id_card_image')
            ]);

            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $WalletRequest = $this->WalletRequestService->store($request);
            return $this->onSuccess(200, 'Approval Wallet Request Send successfully', $WalletRequest);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $WalletRequest)
    {
        $WalletRequest = $this->WalletRequestService->edit($WalletRequest?->id);
        if (empty($WalletRequest)){
            return $this->onSuccess(201, 'Approval Wallet Request', $WalletRequest);
        }
        return $this->onSuccess(200, 'Approval Wallet Request', $WalletRequest);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WalletRequest $WalletRequest)
    {
        try{

            $validator = Validator::make($request->all(), [
                'social_user_id' => 'required|int|exists:social_users,id',
                'user_image' => 'required|image:jpeg,png,jpg',
                'id_card_image' => 'required|image:jpeg,png,jpg'
            ], [], [
                'social_user_id' => trans('app.user_id'),
                'user_image' => trans('app.user_image'),
                'id_card_image' => trans('app.id_card_image')
            ]);

            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $WalletRequest = $this->WalletRequestService->update($request , $WalletRequest);
            return $this->onSuccess(200, 'approval Wallet Request', $WalletRequest);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WalletRequest $WalletRequest)
    {

        try {
            $this->WalletRequestService->destroy($WalletRequest);
            return $this->onSuccess(200, 'Approval Wallet Request deleted');
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }

    }
}
