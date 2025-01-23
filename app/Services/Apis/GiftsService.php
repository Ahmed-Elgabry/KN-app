<?php

namespace App\Services\Apis;

use App\Models\Media;
use App\Repositories\GiftsRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class GiftsService extends BaseService
{
    protected $giftsRepository;

    public function __construct(GiftsRepository $giftsRepository)
    {
        parent::__construct();
        $this->giftsRepository = $giftsRepository;
    }

    public function index()
    {
        return $this->giftsRepository->gifts();
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {

            $store = $this->giftsRepository->store([
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
                'value' => $request->value,
            ]);

            if ($request->image) {
                ini_set('memory_limit', '-1');
                $file = $request->image;
                $image_path = date("Y-m-d") . '/';
                $image_extension = $file->getClientOriginalExtension();
                $image_imageName = date('mdYHis') . uniqid() . '.' . $image_extension;
                File::makeDirectory(public_path('storage/gifts/images/' . $image_path), $mode = 0777, true, true);
                Image::make($file)
                    ->resize(500, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(public_path('storage/gifts/images/' . $image_path) . $image_imageName, 91);
                $image = new Media();
                $image->filename = $image_imageName;
                $image->mime = $file->getClientMimeType();
                $image->type = "gift";
                $image->mediaable_id = $store->id;
                $image->mediaable_type = 'App\Models\Gift';
                $image->url = url('') . '/storage/gifts/images/' . $image_path . $image_imageName;
                $image->save();
            }
            DB::commit();


            return $this->giftsRepository->gift($store->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function edit($id)
    {
        return $this->giftsRepository->gift($id);
    }

    public function update($request)
    {
        DB::beginTransaction();
        try {

            $this->giftsRepository->update([
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
                'value' => $request->value,
            ], $request->id);

            if ($request->image) {
                Media::where('mediaable_id', $request->id)->where('type', 'gift')->delete();
                ini_set('memory_limit', '-1');
                $file = $request->image;
                $image_path = date("Y-m-d") . '/';
                $image_extension = $file->getClientOriginalExtension();
                $image_imageName = date('mdYHis') . uniqid() . '.' . $image_extension;
                File::makeDirectory(public_path('storage/gifts/images/' . $image_path), $mode = 0777, true, true);
                Image::make($file)
                    ->resize(500, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(public_path('storage/gifts/images/' . $image_path) . $image_imageName, 91);
                $image = new Media();
                $image->filename = $image_imageName;
                $image->mime = $file->getClientMimeType();
                $image->type = "gift";
                $image->mediaable_id = $request->id;
                $image->mediaable_type = 'App\Models\Gift';
                $image->url = url('') . '/storage/gifts/images/' . $image_path . $image_imageName;
                $image->save();
            }
            DB::commit();
            return $this->giftsRepository->gift($request->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

    public function delete($request)
    {
        return  $this->giftsRepository->destroy($request->id);
    }
}
