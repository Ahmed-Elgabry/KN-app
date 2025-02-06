<?php

namespace App\Services\Apis;

use App\Models\Advertisement;
use App\Models\Media;
use App\Repositories\AdvertisementRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class AdvertisementService
{
    protected AdvertisementRepository $AdvertisementRepository;

    public function __construct(AdvertisementRepository $AdvertisementRepository)
    {
        $this->AdvertisementRepository = $AdvertisementRepository;
    }

    public function index()
    {
        return $this->AdvertisementRepository->getAllData();
    }

    public function store($request)
    {
        DB::beginTransaction();
        ini_set('memory_limit', '-1');

        try {

            $store = $this->AdvertisementRepository->store($request->except(['advertisement_image' , 'advertisement_additional_image']));

            if ($request->hasFile('advertisement_image')) {
                $advertisement_image = $request->advertisement_image;
                $image_path = date("Y-m-d") . '/';
                $imageName_advertisement_image = date('mdYHis') . uniqid() . '.' . $advertisement_image->getClientOriginalExtension();

                File::makeDirectory(public_path('storage/advertisement/images/' . $image_path), $mode = 0777, true, true);
                Image::make($advertisement_image)
                    ->resize(500, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(public_path('storage/walletRequest/images/' . $image_path) . $imageName_advertisement_image, 91);

                $store->image()->create([
                    'filename' =>  $imageName_advertisement_image,
                    'mime' => $advertisement_image->getClientMimeType(),
                    'type' => 'advertisement_image',
                    'url' => url('') . '/storage/advertisement/images/' . $image_path . $imageName_advertisement_image
                ]);
            }

            if ($request->hasFile('advertisement_additional_image')) {
                $advertisement_additional_image = $request->advertisement_additional_image;
                $image_path = date("Y-m-d") . '/';
                $imageName_advertisement_additional_image = date('mdYHis') . uniqid() . '.' . $advertisement_additional_image->getClientOriginalExtension();

                Image::make($advertisement_additional_image)
                    ->resize(500, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(public_path('storage/walletRequest/images/' . $image_path) . $imageName_advertisement_additional_image, 91);

                $store->image()->create([
                    'filename' =>  $imageName_advertisement_additional_image,
                    'mime' => $advertisement_additional_image->getClientMimeType(),
                    'type' => 'advertisement_additional_image',
                    'url' => url('') . '/storage/advertisement/images/' . $image_path . $imageName_advertisement_additional_image
                ]);
            }

            DB::commit();
            return $this->AdvertisementRepository->store($request);
        } catch (\Throwable $th) {
            DB::rollback();
            errorLog($th->getMessage());

            return false;
        }
    }

    public function edit($id)
    {
        return $this->AdvertisementRepository->getData($id);
    }

    public function update($request, $id)
    {
        DB::beginTransaction();

        try {
            $store = $this->AdvertisementRepository->find($id);
            $image_path = date("Y-m-d") . '/';

            ini_set('memory_limit', '-1');

            if ($request->hasFile('advertisement_image')) {
                $advertisement_image = $request->advertisement_image;
                $oldMedia = Media::when($store?->id , function($q) use ($store) {
                    $q->where('mediaable_id' , $store->id)
                        ->where('type' , 'advertisement_image')
                        ->first();
                });

                if ($oldMedia AND file_exists('storage/advertisement/images/' . $oldMedia->filename)) {
                    unlink(public_path('storage/advertisement/images/' . $oldMedia->filename));
                }

                $imageName_advertisement_image = date('mdYHis') . uniqid() . '.' . $advertisement_image->getClientOriginalExtension();
                Image::make($imageName_advertisement_image)
                    ->resize(500, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(public_path('storage/walletRequest/images/' . $image_path) . $imageName_advertisement_image, 91);

                Media::updateOrCreate([
                    'id' => $oldMedia->id
                ], [
                    'filename' =>  $imageName_advertisement_image,
                    'mime' => $advertisement_image->getClientMimeType(),
                    'type' => 'advertisement_image',
                    'mediaable_id' => $store->id,
                    'mediaable_type' => Advertisement::class,
                    'url' => url('') . '/storage/walletRequest/images/' . $image_path . $imageName_advertisement_image
                ]);
            }

            if ($request->hasFile('advertisement_additional_image')) {
                $advertisement_additional_image = $request->advertisement_additional_image;
                $oldMedia = Media::when($store?->id , function($q) use ($store) {
                    $q->where('mediaable_id' , $store->id)
                        ->where('type' , 'advertisement_additional_image')
                        ->first();
                });

                if ($oldMedia AND file_exists('storage/advertisement/images/' . $oldMedia->filename)) {
                    unlink(public_path('storage/advertisement/images/' . $oldMedia->filename));
                }

                $imageName_advertisement_additional_image = date('mdYHis') . uniqid() . '.' . $advertisement_additional_image->getClientOriginalExtension();
                Image::make($imageName_advertisement_additional_image)
                ->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('storage/advertisement/images/' . $image_path) . $imageName_advertisement_additional_image, 91);

                Media::updateOrCreate([
                    'id' => $oldMedia->id
                ], [
                    'filename' =>  $imageName_advertisement_additional_image,
                    'mime' => $advertisement_image->getClientMimeType(),
                    'type' => 'advertisement_additional_image',
                    'mediaable_id' => $store->id,
                    'mediaable_type' => Advertisement::class,
                    'url' => url('') . '/storage/advertisement/images/' . $image_path . $imageName_advertisement_additional_image
                ]);
            }

            $this->AdvertisementRepository->update($request->except([
                'advertisement_image' ,
                'advertisement_additional_image'
            ]) , $id);

            DB::commit();
            return $this->AdvertisementRepository->find($store->id);
        } catch (\Throwable $th) {
            DB::rollback();
            errorLog($th->getMessage());

            return false;
        }
    }

    public function destroy($request)
    {
        $mediaAdvertisementImage = Media::when($request , function ($q) use ($request) {
            $q->where('mediaable_id' , $request->id)
                ->where('type' , 'advertisement_image')
                ->first();
        });

        if ($mediaAdvertisementImage) {
            if (file_exists('storage/advertisement/images/' . $mediaAdvertisementImage->filename))
                unlink(public_path('storage/advertisement/images/' . $mediaAdvertisementImage->filename));
                $mediaAdvertisementImage->delete();
        }

        $mediaAdvertisementAdditionalImage = Media::when($request , function ($q) use ($request) {
            $q->where('mediaable_id' , $request->id)
                ->where('type' , 'advertisement_additional_image')
                ->first();
        });

        if ($mediaAdvertisementAdditionalImage) {
            if (file_exists('storage/advertisement/images/' . $mediaAdvertisementAdditionalImage->filename))
                unlink(public_path('storage/advertisement/images/' . $mediaAdvertisementAdditionalImage->filename));
                $mediaAdvertisementAdditionalImage->delete();
        }

        return $this->AdvertisementRepository->destroy($request->id);
    }

}
