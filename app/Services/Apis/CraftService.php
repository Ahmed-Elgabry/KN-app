<?php

namespace App\Services\Apis;

use App\Repositories\CraftRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class CraftService extends BaseService
{
    protected $craftRepository;

    public function __construct(CraftRepository $craftRepository)
    {
        parent::__construct();
        $this->craftRepository = $craftRepository;
    }

    public function index()
    {
        return $this->craftRepository->get();
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $store = $this->craftRepository->store([
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
            ]);
            DB::commit();
            return $this->craftRepository->find($store->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function edit($id)
    {
        return $this->craftRepository->find($id);
    }

    public function update($request)
    {
        DB::beginTransaction();
        try {

            $this->craftRepository->update([
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
                'status' => $request->status,
            ], $request->id);

            DB::commit();
            return $this->craftRepository->find($request->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function delete($request)
    {
        return  $this->craftRepository->destroy($request->id);
    }
}
