<?php

namespace App\Services\Apis;

use App\Models\Group;
use App\Repositories\{ GroupRepository , WalletRepository };
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class GroupService extends BaseService
{
    protected $GroupRepository , $walletRepository;
    public function __construct(GroupRepository $GroupRepository,
                                WalletRepository $walletRepository)
    {
        parent::__construct();
        $this->GroupRepository = $GroupRepository;
        $this->walletRepository = $walletRepository;
    }

    public function index()
    {
        return $this->GroupRepository->getAllData();
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {

            $wallet = $this->walletRepository->getFirstWhere(['user_id' => $request->user_id]);

            if ($request?->is_paid AND $wallet->total < $request?->price) {
                return false;
            }

            $store = $this->GroupRepository->store([
                'social_user_id' => $request->social_user_id,
                'group_interest_id' => $request->group_interest_id,
                'group_name' => $request->group_name,
                'group_status' => $request->group_status,
                'is_paid' => $request->is_paid,
                'price' => $request->price,
                'group_description' => $request->group_description,
            ]);

            if ($request->file('main_group_image')) {
                storeImageMedia(
                    $request->file('main_group_image'),
                    'main_group_image',
                    500,
                    Group::class,
                    $store->id,
                    'main_group_image'
                );
            }

            if ($request->is_paid) {
                $this->GroupRepository->deductBalance($request->social_user_id, $request->price);
            }

            DB::commit();
            return $this->GroupRepository->find($store->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function edit($id)
    {
        return $this->GroupRepository->find($id);
    }

    public function update($request)
    {
        DB::beginTransaction();
        try {
            $this->GroupRepository->update([
                'social_user_id' => $request->social_user_id,
                'group_interest_id' => $request->group_interest_id,
                'group_name' => $request->group_name,
                'group_status' => $request->group_status,
                'is_paid' => $request->is_paid,
                'price' => $request->price,
                'group_description' => $request->group_description,
            ], $request->id);

            DB::commit();
            return $this->GroupRepository->find($request->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function delete($request)
    {
        return  $this->GroupRepository->destroy($request->id);
    }
}
