<?php

namespace App\Services\Apis;

use App\Models\Media;
use App\Repositories\AdPlanRepository;
use App\Repositories\EmploymentRepository;
use App\Repositories\HealthRepository;
use App\Repositories\PostRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class HealthService extends BaseService
{
    protected $healthRepository;
    protected $postRepository;
    protected $adPlanRepository;

    public function __construct(HealthRepository $healthRepository,
                                PostRepository $postRepository,
                                AdPlanRepository $adPlanRepository)
    {
        parent::__construct();
        $this->healthRepository = $healthRepository;
        $this->postRepository = $postRepository;
        $this->adPlanRepository = $adPlanRepository;
    }

    public function index()
    {
        return $this->healthRepository->getAllData();
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $post = $this->postRepository->store([
                'user_id' => auth()->id(),
                'city_id' => $request->city_id,
                'type' => 'health',
            ]);

            $ad = $this->adPlanRepository->getFirstWhere(['id' => $request->ad_plan_id]);

            $store = $this->healthRepository->store([
                'name' => $request->name,
                'description' => $request->description,
                'phone' => $request->phone,
                'address' => $request->address,
                'craft' => $request->craft,
                'specialization' => $request->specialization,
                'is_volunteer' => $request->is_volunteer,
                'is_paid' => $request->is_paid,
                'days' => $ad->days,
                'ad_price' => $ad->price,
                'ad_plan_id' => $request->ad_plan_id,
                'city_id' => $request->city_id,
                'user_id' => auth()->id(),
                'post_id' => $post->id,
            ]);

            if ($request->image) {
                ini_set('memory_limit', '-1');
                $file = $request->image;
                $image_path = date("Y-m-d") . '/';
                $image_extension = $file->getClientOriginalExtension();
                $image_imageName = date('mdYHis') . uniqid() . '.' . $image_extension;
                File::makeDirectory(public_path('storage/health/images/' . $image_path), $mode = 0777, true, true);
                Image::make($file)
                    ->resize(500, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(public_path('storage/health/images/' . $image_path) . $image_imageName, 91);
                $image = new Media();
                $image->filename = $image_imageName;
                $image->mime = $file->getClientMimeType();
                $image->type = "main_image";
                $image->mediaable_id = $store->id;
                $image->mediaable_type = 'App\Models\Health';
                $image->url = url('') . '/storage/health/images/' . $image_path . $image_imageName;
                $image->save();
            }
            DB::commit();
            return $this->healthRepository->find($store->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function edit($id)
    {
        return $this->healthRepository->getData($id);
    }

    public function update($request)
    {
        DB::beginTransaction();
        try {

            $ad = $this->adPlanRepository->getFirstWhere(['id' => $request->ad_plan_id]);

            $store = $this->healthRepository->update([
                'name' => $request->name,
                'description' => $request->description,
                'phone' => $request->phone,
                'address' => $request->address,
                'craft' => $request->craft,
                'specialization' => $request->specialization,
                'is_volunteer' => $request->is_volunteer,
                'is_paid' => $request->is_paid,
                'days' => $ad->days,
                'ad_price' => $ad->price,
                'ad_plan_id' => $request->ad_plan_id,
            ], $request->id);

            if ($request->image) {
                ini_set('memory_limit', '-1');
                $file = $request->image;
                $image_path = date("Y-m-d") . '/';
                $image_extension = $file->getClientOriginalExtension();
                $image_imageName = date('mdYHis') . uniqid() . '.' . $image_extension;
                File::makeDirectory(public_path('storage/health/images/' . $image_path), $mode = 0777, true, true);
                Image::make($file)
                    ->resize(500, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(public_path('storage/health/images/' . $image_path) . $image_imageName, 91);
                $image = new Media();
                $image->filename = $image_imageName;
                $image->mime = $file->getClientMimeType();
                $image->type = "main_image";
                $image->mediaable_id = $request->id;
                $image->mediaable_type = 'App\Models\Health';
                $image->url = url('') . '/storage/health/images/' . $image_path . $image_imageName;
                $image->save();
            }

            DB::commit();
            return $this->healthRepository->find($request->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function delete($request)
    {
        return  $this->healthRepository->destroy($request->id);
    }
}
