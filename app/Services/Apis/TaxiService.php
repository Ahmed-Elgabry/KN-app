<?php

namespace App\Services\Apis;

use App\Models\Media;
use App\Repositories\AdPlanRepository;
use App\Repositories\EmploymentRepository;
use App\Repositories\HealthRepository;
use App\Repositories\PostRepository;
use App\Repositories\TaxiPlaceRepository;
use App\Repositories\TaxiRepository;
use App\Services\BaseService;
use http\Env\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class TaxiService extends BaseService
{
    protected $taxiRepository;
    protected $taxiPlaceRepository;
    protected $postRepository;
    protected $adPlanRepository;

    public function __construct(TaxiRepository $taxiRepository,
                                TaxiPlaceRepository $taxiPlaceRepository,
                                PostRepository $postRepository,
                                AdPlanRepository $adPlanRepository)
    {
        parent::__construct();
        $this->taxiRepository = $taxiRepository;
        $this->taxiPlaceRepository = $taxiPlaceRepository;
        $this->postRepository = $postRepository;
        $this->adPlanRepository = $adPlanRepository;
    }

    public function index()
    {
        return $this->taxiRepository->getAllData();
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $post = $this->postRepository->store([
                'user_id' => auth()->id(),
                'city_id' => $request->city_id,
                'type' => 'taxi',
            ]);

            $ad = $this->adPlanRepository->getFirstWhere(['id' => $request->ad_plan_id]);

            $store = $this->taxiRepository->store([
                'car_type' => $request->car_type,
                'date' => $request->date,
                'phone' => $request->phone,
                'is_aired' => $request->is_aired,
                'from' => $request->from,
                'to' => $request->to,
                'is_paid' => $request->is_paid,
                'days' => $ad->days,
                'ad_price' => $ad->price,
                'ad_plan_id' => $request->ad_plan_id,
                'city_id' => $request->city_id,
                'user_id' => auth()->id(),
                'post_id' => $post->id,
            ]);

            foreach ($request->places as $key => $place){
                $this->taxiPlaceRepository->store([
                   'taxi_id' => $store->id,
                   'place' => $place
                ]);
            }

            DB::commit();
            return $this->taxiRepository->find($store->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function edit($id)
    {
        return $this->taxiRepository->getData($id);
    }

    public function update($request)
    {
        DB::beginTransaction();
        try {

            $ad = $this->adPlanRepository->getFirstWhere(['id' => $request->ad_plan_id]);

            $store = $this->taxiRepository->update([
                'car_type' => $request->car_type,
                'date' => $request->date,
                'phone' => $request->phone,
                'is_aired' => $request->is_aired,
                'from' => $request->from,
                'to' => $request->to,
                'is_paid' => $request->is_paid,
                'days' => $ad->days,
                'ad_price' => $ad->price,
                'ad_plan_id' => $request->ad_plan_id,
            ], $request->id);



            $this->taxiPlaceRepository->deleteData($request->id);

            foreach ($request->places as $key => $place){
                $this->taxiPlaceRepository->store([
                    'taxi_id' => $request->id,
                    'place' => $place
                ]);
            }

            DB::commit();
            return $this->taxiRepository->find($request->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function delete($request)
    {
        return  $this->taxiRepository->destroy($request->id);
    }
}
