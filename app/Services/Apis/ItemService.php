<?php

namespace App\Services\Apis;

use App\Repositories\ItemRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class ItemService extends BaseService
{
    protected $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        parent::__construct();
        $this->itemRepository = $itemRepository;
    }

    public function index()
    {
        return $this->itemRepository->get();
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $store = $this->itemRepository->store([
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
            ]);
            DB::commit();
            return $this->itemRepository->find($store->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function edit($id)
    {
        return $this->itemRepository->find($id);
    }

    public function update($request)
    {
        DB::beginTransaction();
        try {

            $this->itemRepository->update([
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
                'status' => $request->status,
            ], $request->id);

            DB::commit();
            return $this->itemRepository->find($request->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function delete($request)
    {
        return  $this->itemRepository->destroy($request->id);
    }
}
