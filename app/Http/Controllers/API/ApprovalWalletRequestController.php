<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApprovalWalletRequest as RequestsApprovalWalletRequest;
use App\Http\Traits\{ HelperApi , ImageProcessing };
use App\Models\ApprovalWalletRequest;
use App\Services\Apis\ApprovalWalletRequestService;

class ApprovalWalletRequestController extends Controller
{
    use HelperApi, ImageProcessing;

    protected ApprovalWalletRequestService $ApprovalWalletRequestService;

    public function __construct(ApprovalWalletRequestService $ApprovalWalletRequestService)
    {
        $this->ApprovalWalletRequestService = $ApprovalWalletRequestService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $ApprovalWalletRequests = $this->ApprovalWalletRequestService->index();
            return $this->onSuccess(200, 'ApprovalWalletRequests', $ApprovalWalletRequests);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestsApprovalWalletRequest $request)
    {
        try{
            $ApprovalWalletRequest = $this->ApprovalWalletRequestService->store($request);
            return $this->onSuccess(200, 'Approval Wallet Request Send successfully', $ApprovalWalletRequest);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApprovalWalletRequest $approvalWalletRequest)
    {
        $ApprovalWalletRequest = $this->ApprovalWalletRequestService->edit($approvalWalletRequest?->id);
        if (empty($ApprovalWalletRequest)){
            return $this->onSuccess(201, 'Approval Wallet Request', $ApprovalWalletRequest);
        }
        return $this->onSuccess(200, 'Approval Wallet Request', $ApprovalWalletRequest);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestsApprovalWalletRequest $request, ApprovalWalletRequest $approvalWalletRequest)
    {
        try{
            $approvalWalletRequest = $this->ApprovalWalletRequestService->update($request , $approvalWalletRequest);
            return $this->onSuccess(200, 'approval Wallet Request', $approvalWalletRequest);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApprovalWalletRequest $approvalWalletRequest)
    {

        try {
            $this->ApprovalWalletRequestService->destroy($approvalWalletRequest);
            return $this->onSuccess(200, 'Approval Wallet Request deleted');
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }

    }
}
