<?php

namespace App\Services\Apis;

use App\Repositories\WalletRequestRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class WalletRequestService extends BaseService
{
    protected WalletRequestRepository $WalletRequestRepository;

    public function __construct(WalletRequestRepository $WalletRequestRepository) {
        $this->WalletRequestRepository = $WalletRequestRepository;
    }

    public function index()
    {
        return $this->WalletRequestRepository->get();
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

                $store = $this->WalletRequestRepository->store([
                    'user_id' => $request->user_id,
                    'user_image' => $user_image,
                    'id_card_image' => $id_card_image
                ]);
            }

            DB::commit();
            return $this->WalletRequestRepository->find($store->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());

            return false;
        }
    }

    public function edit($id)
    {
        return $this->WalletRequestRepository->find($id);
    }

    public function update($request , $id)
    {
        DB::beginTransaction();

        try {
            $approvalRequest = $this->WalletRequestRepository->find($id);

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

            $this->WalletRequestRepository->update([
                'user_id' => $request->user_id,
                'user_image' => $user_image,
                'id_card_image' => $id_card_image,
                'status' => $request->status,
            ], $id);

            DB::commit();
            return $this->WalletRequestRepository->find($id);
        } catch (\Exception $e) {

            DB::rollBack();
            errorLog($e->getMessage());

            return false;
        }

    }

    public function destroy($request)
    {
        return  $this->WalletRequestRepository->destroy($request->id);
    }

}
