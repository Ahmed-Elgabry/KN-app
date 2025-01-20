<?php

namespace App\Services\Apis;

use App\Models\BillItems;
use App\Models\Media;
use App\Models\Type;
use App\Repositories\DiscountProductsRepository;
use App\Repositories\DiscountRepository;
use App\Repositories\DiscountTimesRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class DiscountService extends BaseService
{
    protected $discountRepository;
    protected $discountProductsRepository;
    protected $discountTimesRepository;

    public function __construct(DiscountRepository $discountRepository,
                                DiscountProductsRepository $discountProductsRepository,
                                DiscountTimesRepository $discountTimesRepository)
    {
        parent::__construct();
        $this->discountRepository = $discountRepository;
        $this->discountProductsRepository = $discountProductsRepository;
        $this->discountTimesRepository = $discountTimesRepository;
    }

    public function index()
    {
        return $this->discountRepository->getAllData();
    }

    public function userDiscounts()
    {
        return $this->discountRepository->userDiscounts();
    }

    public function cityDiscounts($id)
    {
        return $this->discountRepository->cityDiscounts($id);
    }

    public function store($request)
    {
         DB::beginTransaction();
        try {
            $store = $this->discountRepository->store([
                'name' => $request->name,
                'address' => $request->address,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
                'description' => $request->description,
                'discount_percentage' => $request->discount_percentage,
                'discount_percentage_text' => $request->discount_percentage_text,
                'daly_discount_text' => $request->daly_discount_text,
                'discount_type' => $request->discount_type,
                'daly_discount' => $request->daly_discount,
                'all_work_hours' => $request->all_work_hours,
                'from' => $request->from,
                'to' => $request->to,
                'city_id' => $request->city_id,
                'user_id' => auth()->id(),
            ]);

            if ($request->image) {
                ini_set('memory_limit', '-1');
                $file = $request->image;
                $image_path = date("Y-m-d") . '/';
                    $image_extension = $file->getClientOriginalExtension();
                    $image_imageName = date('mdYHis') . uniqid() . '.' . $image_extension;
                    File::makeDirectory(public_path('storage/discount/images/' . $image_path), $mode = 0777, true, true);
                    Image::make($file)
                        ->resize(500, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->save(public_path('storage/discount/images/' . $image_path) . $image_imageName, 91);
                    $image = new Media();
                    $image->filename = $image_imageName;
                    $image->mime = $file->getClientMimeType();
                    $image->type = "main_image";
                    $image->mediaable_id = $store->id;
                    $image->mediaable_type = 'App\Models\Discount';
                    $image->url = url('') . '/storage/discount/images/' . $image_path . $image_imageName;
                    $image->save();
            }

            if ($request->image_list) {
                foreach ($request->image_list as $image){
                    ini_set('memory_limit', '-1');
                    $file = $image;
                    $image_path = date("Y-m-d") . '/';
                    $image_extension = $file->getClientOriginalExtension();
                    $image_imageName = date('mdYHis') . uniqid() . '.' . $image_extension;
                    File::makeDirectory(public_path('storage/discount/images/' . $image_path), $mode = 0777, true, true);
                    Image::make($file)
                        ->resize(500, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->save(public_path('storage/discount/images/' . $image_path) . $image_imageName, 91);
                    $image = new Media();
                    $image->filename = $image_imageName;
                    $image->mime = $file->getClientMimeType();
                    $image->type = "image_list";
                    $image->mediaable_id = $store->id;
                    $image->mediaable_type = 'App\Models\Discount';
                    $image->url = url('') . '/storage/discount/images/' . $image_path . $image_imageName;
                    $image->save();

                }
            }

            if ($request->discount_type == 1){
                foreach ($request->products as $product){
                    $this->discountProductsRepository->store([
                        'name' => $product['name'],
                        'price_before' => $product['price_before'],
                        'price_after' => $product['price_after'],
                        'discount_id' => $store->id,
                    ]);

                }
            }

            $this->discountTimesRepository->store([
                'discount_from' => $request->work_hours['discount_from'],
                'discount_to' => $request->work_hours['discount_to'],
                'work_from' => $request->work_hours['work_from'],
                'work_to' => $request->work_hours['work_to'],
                'description' => $request->work_hours['description'],
                'discount_id' => $store->id,
            ]);

            $storedDiscount = $this->discountRepository->getData($store->id);
            DB::commit();
           return $storedDiscount;
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function edit($id)
    {
        return $this->discountRepository->getData($id);
    }

    public function update($request)
    {
        DB::beginTransaction();
        try {
            $store = $this->discountRepository->update([
                'name' => $request->name,
                'address' => $request->address,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
                'description' => $request->description,
                'discount_percentage' => $request->discount_percentage,
                'discount_percentage_text' => $request->discount_percentage_text,
                'daly_discount_text' => $request->daly_discount_text,
                'discount_type' => $request->discount_type,
                'daly_discount' => $request->daly_discount,
                'all_work_hours' => $request->all_work_hours,
                'from' => $request->from,
                'to' => $request->to,
                'city_id' => $request->city_id,
            ], $request->id);

            if ($request->image) {
                Media::where('mediaable_id', $request->id)->where('type', 'main_image')->delete();
                ini_set('memory_limit', '-1');
                $file = $request->image;
                $image_path = date("Y-m-d") . '/';
                $image_extension = $file->getClientOriginalExtension();
                $image_imageName = date('mdYHis') . uniqid() . '.' . $image_extension;
                File::makeDirectory(public_path('storage/discount/images/' . $image_path), $mode = 0777, true, true);
                Image::make($file)
                    ->resize(500, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(public_path('storage/discount/images/' . $image_path) . $image_imageName, 91);
                $image = new Media();
                $image->filename = $image_imageName;
                $image->mime = $file->getClientMimeType();
                $image->type = "main_image";
                $image->mediaable_id = $request->id;
                $image->mediaable_type = 'App\Models\Discount';
                $image->url = url('') . '/storage/discount/images/' . $image_path . $image_imageName;
                $image->save();
            }

            if ($request->image_list) {
                Media::where('mediaable_id', $request->id)->where('type', 'image_list')->delete();
                foreach ($request->image_list as $image){
                    ini_set('memory_limit', '-1');
                    $file = $image;
                    $image_path = date("Y-m-d") . '/';
                    $image_extension = $file->getClientOriginalExtension();
                    $image_imageName = date('mdYHis') . uniqid() . '.' . $image_extension;
                    File::makeDirectory(public_path('storage/discount/images/' . $image_path), $mode = 0777, true, true);
                    Image::make($file)
                        ->resize(500, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->save(public_path('storage/discount/images/' . $image_path) . $image_imageName, 91);
                    $image = new Media();
                    $image->filename = $image_imageName;
                    $image->mime = $file->getClientMimeType();
                    $image->type = "image_list";
                    $image->mediaable_id = $request->id;
                    $image->mediaable_type = 'App\Models\Discount';
                    $image->url = url('') . '/storage/discount/images/' . $image_path . $image_imageName;
                    $image->save();

                }
            }

            $this->discountProductsRepository->where('discount_id', $request->id)->delete();
            if ($request->discount_type == 1){
                foreach ($request->products as $product){
                    $this->discountProductsRepository->store([
                        'name' => $product['name'],
                        'price_before' => $product['price_before'],
                        'price_after' => $product['price_after'],
                        'discount_id' => $request->id,
                    ]);

                }
            }
            $this->discountTimesRepository->where('discount_id', $request->id)->delete();
            $this->discountTimesRepository->store([
                'discount_from' => $request->work_hours['discount_from'],
                'discount_to' => $request->work_hours['discount_to'],
                'work_from' => $request->work_hours['work_from'],
                'work_to' => $request->work_hours['work_to'],
                'description' => $request->work_hours['description'],
                'discount_id' => $request->id,
            ]);

            $updatedDiscount = $this->discountRepository->getData($request->id);
            DB::commit();
            return $updatedDiscount;
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function delete($request)
    {
        return  $this->discountRepository->destroy($request->id);
    }

}
