<?php

namespace App\Services\Apis;

use App\Repositories\ApprovalWalletRequestRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class ApprovalWalletRequestService extends BaseService
{
    protected ApprovalWalletRequestRepository $ApprovalWalletRequestRepository;

    public function __construct(ApprovalWalletRequestRepository $ApprovalWalletRequestRepository) {
        $this->ApprovalWalletRequestRepository = $ApprovalWalletRequestRepository;
    }

    public function index()
    {
        return $this->ApprovalWalletRequestRepository->get();
    }

    public function store($request)
    {
        DB::beginTransaction();

        try {

            if ($request->user_id AND $request->user_image AND $request->id_card_image) {
                $user_image = time() . '.' . request()->user_image->getClientOriginalExtension();
                request()->user_image->move(public_path('images/ApprovalWalletRequest'), $user_image);
                $id_card_image = time() . '.' . request()->id_card_image->getClientOriginalExtension();
                request()->id_card_image->move(public_path('images/ApprovalWalletRequest'), $id_card_image);

                $store = $this->ApprovalWalletRequestRepository->store([
                    'user_id' => $request->user_id,
                    'user_image' => $user_image,
                    'id_card_image' => $id_card_image
                ]);
            }

            DB::commit();
            return $this->ApprovalWalletRequestRepository->find($store->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());

            return false;
        }
    }

    public function edit($id)
    {
        return $this->ApprovalWalletRequestRepository->find($id);
    }

    public function update($request , $id)
    {
        DB::beginTransaction();

        try {
            $approvalRequest = $this->ApprovalWalletRequestRepository->find($id);

            if ( $request->hasFile('user_image') ){

                if (file_exists('images/ApprovalWalletRequest/' . $approvalRequest->user_image)) {
                    unlink(public_path('images/ApprovalWalletRequest/' . $approvalRequest->user_image));
                }

                $user_image = time() . '.' . $request->user_image->getClientOriginalExtension();
                $request->user_image->move(public_path('images/ApprovalWalletRequest'), $user_image);

            }

            if ( $request->hasFile('id_card_image')) {

                if (file_exists('images/ApprovalWalletRequest/' . $approvalRequest->id_card_image)) {
                    unlink(public_path('images/ApprovalWalletRequest/' . $approvalRequest->id_card_image));
                }

                $id_card_image = time() . '.' . $request->id_card_image->getClientOriginalExtension();
                $request->id_card_image->move(public_path('images/ApprovalWalletRequest'), $id_card_image);
            }

            $this->ApprovalWalletRequestRepository->update([
                'user_id' => $request->user_id,
                'user_image' => $user_image,
                'id_card_image' => $id_card_image,
                'status' => $request->status,
            ], $id);

            DB::commit();
            return $this->ApprovalWalletRequestRepository->find($id);
        } catch (\Exception $e) {

            DB::rollBack();
            errorLog($e->getMessage());

            return false;
        }

    }

    public function destroy($request)
    {
        return  $this->ApprovalWalletRequestRepository->destroy($request->id);
    }

}
