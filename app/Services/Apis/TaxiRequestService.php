<?php

namespace App\Services\Apis;


use App\Repositories\TaxiRequestRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;


class TaxiRequestService extends BaseService
{
    protected $taxiRequestRepository;

    public function __construct(TaxiRequestRepository $taxiRequestRepository)
    {
        parent::__construct();
        $this->taxiRequestRepository = $taxiRequestRepository;
    }

    public function index()
    {
        return $this->taxiRequestRepository->get();
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {

            $store = $this->taxiRequestRepository->store([
                'taxi_id' => $request->taxi_id,
                'user_id' => auth()->id(),
                'name' => $request->name,
                'phone' => $request->phone,
                'from' => $request->from,
                'from_longitude' => $request->from_longitude,
                'from_latitude' => $request->from_latitude,
                'to' => $request->to,
                'to_longitude' => $request->to_longitude,
                'to_latitude' => $request->to_latitude,
                'price' => $request->price,
                'notes' => $request->notes,
            ]);

            DB::commit();
            return $this->taxiRequestRepository->find($store->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function edit($id)
    {
        return $this->taxiRequestRepository->find($id);
    }

    public function update($request)
    {
        DB::beginTransaction();
        try {

            $store = $this->taxiRequestRepository->update([
                'taxi_id' => $request->taxi_id,
                'name' => $request->name,
                'phone' => $request->phone,
                'from' => $request->from,
                'from_longitude' => $request->from_longitude,
                'from_latitude' => $request->from_latitude,
                'to' => $request->to,
                'to_longitude' => $request->to_longitude,
                'to_latitude' => $request->to_latitude,
                'price' => $request->price,
                'notes' => $request->notes,
            ], $request->id);

            DB::commit();
            return $this->taxiRequestRepository->find($request->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function delete($request)
    {
        return  $this->taxiRequestRepository->destroy($request->id);
    }
}
