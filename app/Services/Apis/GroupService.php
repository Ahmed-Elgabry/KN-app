<?php

namespace App\Services\Apis;

use App\Models\Group;
use App\Repositories\{ GroupRepository };
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class GroupService extends BaseService
{
    protected $GroupRepository , $walletRepository , $walletService;
    public function __construct(GroupRepository $GroupRepository,
                                WalletService $walletService)
    {
        parent::__construct();
        $this->GroupRepository = $GroupRepository;
        $this->walletService = $walletService;
    }

    public function index()
    {
        return $this->GroupRepository->getAllData();
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            if ($request?->is_paid) {
                $this->walletService->decreaseWallet($request);
            }
            $store = $this->GroupRepository->store([
                'social_user_id' => $request->user_id,
                'group_interest_id' => $request->group_interest_id,
                'group_name' => $request->group_name,
                'group_status' => $request->group_status,
                'is_paid' => $request->is_paid,
                'price' => $request->balance,
                'group_description' => $request->group_description,
            ]);
            if ($request->hasFile('main_group_image')) {
                storeImageMedia(
                    $request->file('main_group_image'),
                    'main_group_image',
                    500,
                    Group::class,
                    $store->id,
                    'main_group_image'
                );
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
        return $this->GroupRepository->getData($id);
    }

    public function userGroups($id)
    {
        return $this->GroupRepository->getUserData($id);
    }

    public function update($request)
    {
        DB::beginTransaction();
        try {
            $group = $this->GroupRepository->getFirstWhere(['id' => $request->id]);
            $this->GroupRepository->update([
                'social_user_id' => $request->social_user_id,
                'group_interest_id' => $request->group_interest_id,
                'group_name' => $request->group_name,
                'group_status' => $request->group_status,
                'is_paid' => $request->is_paid,
                'price' => $request->price,
                'group_description' => $request->group_description,
            ], $request->id);
            if ($request->hasFile('main_group_image')) {
                if ($group->media) {
                    deleteImageMedia(public_path(str_replace(url('/'), '', $group->media->url)));
                    $group->media->delete();
                }
                storeImageMedia(
                    $request->file($request->main_group_image),
                   'main_group_image',
                    500,
                    Group::class,
                    $request->id,
                   'main_group_image'
                );
            }
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
        DB::beginTransaction();
        try {
            $group = $this->GroupRepository->getFirstWhere(['id' => $request->id]);
            if ($group->media) {
                deleteImageMedia(public_path(str_replace(url('/'), '', $group->image->url)));
                $group->image->delete();
            }
            $status = true;
            $group->delete();
            DB::commit();
            return $status;
        } catch (\Throwable $th) {
            DB::rollBack();
            errorLog($th->getMessage());
            return false;
        }
    }
}
