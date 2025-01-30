<?php

namespace App\Services\Apis;

use App\Repositories\AdPlanRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class AdPlanService extends BaseService
{
    protected $adPlanRepository;

    public function __construct(AdPlanRepository $adPlanRepository)
    {
        parent::__construct();
        $this->adPlanRepository = $adPlanRepository;
    }

    public function index()
    {
        return $this->adPlanRepository->get();
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $store = $this->adPlanRepository->store([
                'title' => $request->title,
                'days' => $request->days,
                'price' => $request->price,
                'status' => $request->status,
            ]);
            DB::commit();
            return $this->adPlanRepository->find($store->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function edit($id)
    {
        return $this->adPlanRepository->find($id);
    }

    public function update($request)
    {
        DB::beginTransaction();
        try {

            $this->adPlanRepository->update([
                'title' => $request->title,
                'days' => $request->days,
                'price' => $request->price,
                'status' => $request->status,
            ], $request->id);

            DB::commit();
            return $this->adPlanRepository->find($request->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function delete($request)
    {
        return  $this->adPlanRepository->destroy($request->id);
    }
}
