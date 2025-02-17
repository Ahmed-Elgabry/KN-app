<?php

namespace App\Services\Apis;

use App\Repositories\GroupInterestRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class GroupInterestService extends BaseService
{
    protected $GroupInterestRepository;

    public function __construct(GroupInterestRepository $GroupInterestRepository)
    {
        parent::__construct();
        $this->GroupInterestRepository = $GroupInterestRepository;
    }

    public function index()
    {
        return $this->GroupInterestRepository->get();
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $store = $this->GroupInterestRepository->store([
                'name_ar' => $request->name_ar,
                'name_en' => $request->name_en,
                'status' => $request->status,
            ]);
            DB::commit();
            return $this->GroupInterestRepository->find($store->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function edit($id)
    {
        return $this->GroupInterestRepository->find($id);
    }

    public function update($request)
    {
        DB::beginTransaction();
        try {

            $this->GroupInterestRepository->update([
                'name_ar' => $request->name_ar,
                'name_en' => $request->name_en,
                'status' => $request->status,
            ], $request->id);

            DB::commit();
            return $this->GroupInterestRepository->find($request->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function delete($request)
    {
        return  $this->GroupInterestRepository->destroy($request->id);
    }
}
