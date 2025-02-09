<?php

namespace App\Services\Apis;

use App\Models\Media;
use App\Repositories\AdPlanRepository;
use App\Repositories\CityMarketRepository;
use App\Repositories\EmploymentRepository;
use App\Repositories\PostRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class CityMarketService extends BaseService
{
    protected $cityMarketRepository;
    protected $postRepository;
    protected $adPlanRepository;

    public function __construct(CityMarketRepository $cityMarketRepository,
                                PostRepository $postRepository,
                                AdPlanRepository $adPlanRepository)
    {
        parent::__construct();
        $this->cityMarketRepository = $cityMarketRepository;
        $this->postRepository = $postRepository;
        $this->adPlanRepository = $adPlanRepository;
    }

    public function index()
    {
        return $this->cityMarketRepository->getAllData();
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $post = $this->postRepository->store([
                'user_id' => auth()->id(),
                'city_id' => $request->city_id,
                'type' => 'city_market',
            ]);

            $ad = $this->adPlanRepository->getFirstWhere(['id' => $request->ad_plan_id]);

            $store = $this->cityMarketRepository->store([
                'name' => $request->name,
                'description' => $request->description,
                'item_id' => $request->item_id,
                'phone' => $request->phone,
                'price' => $request->price,
                'address' => $request->address,
                'is_paid' => $request->is_paid,
                'is_auction' => $request->is_auction,
                'days' => $ad->days,
                'ad_price' => $ad->price,
                'ad_plan_id' => $request->ad_plan_id,
                'city_id' => $request->city_id,
                'user_id' => auth()->id(),
                'post_id' => $post->id,
            ]);

            if ($request->images) {
                foreach ($request->images as $image) {
                    ini_set('memory_limit', '-1');
                    $file = $image;
                    $image_path = date("Y-m-d") . '/';
                    $image_extension = $file->getClientOriginalExtension();
                    $image_imageName = date('mdYHis') . uniqid() . '.' . $image_extension;
                    File::makeDirectory(public_path('storage/city_market/images/' . $image_path), $mode = 0777, true, true);
                    Image::make($file)
                        ->resize(500, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->save(public_path('storage/city_market/images/' . $image_path) . $image_imageName, 91);
                    $image = new Media();
                    $image->filename = $image_imageName;
                    $image->mime = $file->getClientMimeType();
                    $image->type = "image_list";
                    $image->mediaable_id = $store->id;
                    $image->mediaable_type = 'App\Models\CityMarket';
                    $image->url = url('') . '/storage/city_market/images/' . $image_path . $image_imageName;
                    $image->save();
                }

            }
            DB::commit();
            return $this->cityMarketRepository->find($store->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function edit($id)
    {
        return $this->cityMarketRepository->getData($id);
    }

    public function update($request)
    {
        DB::beginTransaction();
        try {

            $ad = $this->adPlanRepository->getFirstWhere(['id' => $request->ad_plan_id]);

            $store = $this->cityMarketRepository->update([
                'name' => $request->name,
                'description' => $request->description,
                'item_id' => $request->item_id,
                'phone' => $request->phone,
                'price' => $request->price,
                'address' => $request->address,
                'is_paid' => $request->is_paid,
                'is_auction' => $request->is_auction,
                'days' => $ad->days,
                'ad_price' => $ad->price,
                'ad_plan_id' => $request->ad_plan_id,
                'city_id' => $request->city_id,
            ], $request->id);

            if ($request->images) {
                foreach ($request->images as $image) {
                    ini_set('memory_limit', '-1');
                    $file = $image;
                    $image_path = date("Y-m-d") . '/';
                    $image_extension = $file->getClientOriginalExtension();
                    $image_imageName = date('mdYHis') . uniqid() . '.' . $image_extension;
                    File::makeDirectory(public_path('storage/city_market/images/' . $image_path), $mode = 0777, true, true);
                    Image::make($file)
                        ->resize(500, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->save(public_path('storage/city_market/images/' . $image_path) . $image_imageName, 91);
                    $image = new Media();
                    $image->filename = $image_imageName;
                    $image->mime = $file->getClientMimeType();
                    $image->type = "image_list";
                    $image->mediaable_id = $store->id;
                    $image->mediaable_type = 'App\Models\CityMarket';
                    $image->url = url('') . '/storage/city_market/images/' . $image_path . $image_imageName;
                    $image->save();
                }

            }

            DB::commit();
            return $this->cityMarketRepository->find($request->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function delete($request)
    {
        return  $this->cityMarketRepository->destroy($request->id);
    }
}
