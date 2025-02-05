<?php

namespace App\Services\Apis;

use App\Models\{ Media , WalletRequest };
use App\Repositories\WalletRequestRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class WalletRequestService extends BaseService
{
    protected WalletRequestRepository $WalletRequestRepository;

    public function __construct(WalletRequestRepository $WalletRequestRepository) {
        $this->WalletRequestRepository = $WalletRequestRepository;
    }

    public function index()
    {
        return $this->WalletRequestRepository->get();
    }

    public function store($request)
    {
        DB::beginTransaction();

        try {

            if ($request->social_user_id AND $request->user_image AND $request->id_card_image) {

                $store = $this->WalletRequestRepository->store([
                    'social_user_id' => $request->social_user_id,
                ]);

                ini_set('memory_limit', '-1');
                $user_image = $request->user_image;
                $image_path = date("Y-m-d") . '/';
                $imageName_user_image = date('mdYHis') . uniqid() . '.' . $user_image->getClientOriginalExtension();

                File::makeDirectory(public_path('storage/walletRequest/images/' . $image_path), $mode = 0777, true, true);
                Image::make($user_image)
                    ->resize(500, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(public_path('storage/walletRequest/images/' . $image_path) . $imageName_user_image, 91);

                Media::create([
                    'filename' =>  $imageName_user_image,
                    'mime' => $user_image->getClientMimeType(),
                    'type' => 'user_image',
                    'mediaable_id' => $store->id,
                    'mediaable_type' => WalletRequest::class,
                    'url' => url('') . '/storage/walletRequest/images/' . $image_path . $imageName_user_image
                ]);

                $id_card_image = $request->id_card_image;
                $imageName_id_card_image = date('mdYHis') . uniqid() . '.' . $id_card_image->getClientOriginalExtension();

                Image::make($id_card_image)
                    ->resize(500, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(public_path('storage/walletRequest/images/' . $image_path) . $imageName_id_card_image, 91);

                Media::create([
                    'filename' =>  $id_card_image,
                    'mime' => $user_image->getClientMimeType(),
                    'type' => 'id_card_image',
                    'mediaable_id' => $store->id,
                    'mediaable_type' => WalletRequest::class,
                    'url' => url('') . '/storage/walletRequest/images/' . $image_path . $imageName_id_card_image
                ]);

            }

            DB::commit();
            return $this->WalletRequestRepository->find($store->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());

            return false;
        }
    }

    public function edit($id)
    {
        return $this->WalletRequestRepository->find($id);
    }

    public function update($request , $id)
    {
        DB::beginTransaction();

        try {
            $approvalRequest = $this->WalletRequestRepository->find($id);
            $image_path = date("Y-m-d") . '/';

            ini_set('memory_limit', '-1');

            if ( $request->hasFile($request->user_image) ) {

                $user_image = $request->user_image;

                $oldMedia = Media::when($approvalRequest?->id , function($q) use ($approvalRequest) {
                    $q->where('mediaable_id' , $approvalRequest->id)
                        ->where('type' , 'user_image')
                        ->first();
                });

                if ($oldMedia AND file_exists('storage/walletRequest/images/' . $oldMedia->filename)) {
                    unlink(public_path('storage/walletRequest/images/' . $oldMedia->filename));
                }

                $imageName_user_image = date('mdYHis') . uniqid() . '.' . $user_image->getClientOriginalExtension();
                Image::make($imageName_user_image)
                    ->resize(500, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(public_path('storage/walletRequest/images/' . $image_path) . $imageName_user_image, 91);

                Media::updateOrCreate([
                    'id' => $oldMedia->id
                ], [
                    'filename' =>  $imageName_user_image,
                    'mime' => $user_image->getClientMimeType(),
                    'type' => 'user_image',
                    'mediaable_id' => $approvalRequest->id,
                    'mediaable_type' => WalletRequest::class,
                    'url' => url('') . '/storage/walletRequest/images/' . $image_path . $imageName_user_image
                ]);

            }

            if ( $request->hasFile($request->id_card_image) ) {
                $id_card_image = $request->id_card_image;

                $oldMedia = Media::when($approvalRequest?->id , function($q) use ($approvalRequest) {
                    $q->where('mediaable_id' , $approvalRequest->id)
                        ->where('type' , 'id_card_image')
                        ->first();
                });

                if ($oldMedia AND file_exists('storage/walletRequest/images/' . $oldMedia->filename)) {
                    unlink(public_path('storage/walletRequest/images/' . $oldMedia->filename));
                }

                $imageName_id_card_image = date('mdYHis') . uniqid() . '.' . $id_card_image->getClientOriginalExtension();
                Image::make($imageName_id_card_image)
                    ->resize(500, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(public_path('storage/walletRequest/images/' . $image_path) . $imageName_id_card_image, 91);

                Media::updateOrCreate([
                    'id' => $oldMedia->id
                ], [
                    'filename' =>  $imageName_id_card_image,
                    'mime' => $id_card_image->getClientMimeType(),
                    'type' => 'id_card_image',
                    'mediaable_id' => $approvalRequest->id,
                    'mediaable_type' => WalletRequest::class,
                    'url' => url('') . '/storage/walletRequest/images/' . $image_path . $imageName_id_card_image
                ]);
            }


            $this->WalletRequestRepository->update([
                'social_user_id' => $request->social_user_id,
                'status' => $request->status,
            ], $id);

            DB::commit();
            return $this->WalletRequestRepository->find($id);
        } catch (\Exception $e) {

            DB::rollBack();
            errorLog($e->getMessage());

            return false;
        }

    }

    public function destroy($request)
    {
        $mediaUserImage = Media::when($request , function ($q) use ($request) {
            $q->where('mediaable_id' , $request->id)
                ->where('type' , 'user_image')
                ->first();
        });

        if ($mediaUserImage) {
            if (file_exists('storage/walletRequest/images/' . $mediaUserImage->filename))
                unlink(public_path('storage/walletRequest/images/' . $mediaUserImage->filename));
                $mediaUserImage->delete();
        }

        $mediaIdCardImage = Media::when($request , function ($q) use ($request) {
            $q->where('mediaable_id' , $request->id)
                ->where('type' , 'id_card_image')
                ->first();
        });

        if ($mediaIdCardImage) {
            if (file_exists('storage/walletRequest/images/' . $mediaIdCardImage->filename))
                unlink(public_path('storage/walletRequest/images/' . $mediaIdCardImage->filename));
                $mediaIdCardImage->delete();
        }

        return $this->WalletRequestRepository->destroy($request->id);
    }

}
