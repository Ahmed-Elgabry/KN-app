<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\HelperApi;
use App\Http\Traits\ImageProcessing;
use App\Services\Apis\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class WalletController extends Controller
{
    use HelperApi, ImageProcessing;

    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function createWallet(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:social_users,id',
                'name' => 'required',
            ], [], [
                'user_id' => trans('app.user_id'),
                'name' => trans('app.name'),
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $wallet = $this->walletService->createWallet($request);
            if ($wallet){
                return $this->onSuccess(200, 'wallet created successfully', $wallet);
            }
            return $this->onSuccess(200, 'already exists', $wallet);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function increaseWallet(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:social_users,id',
                'name' => 'required',
                'balance' => 'required',
                'description' => 'nullable',
            ], [], [
                'user_id' => trans('app.user_id'),
                'name' => trans('app.name'),
                'balance' => trans('app.balance'),
                'description' => trans('app.description'),
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $wallet = $this->walletService->increaseWallet($request);
            if ($wallet){
                return $this->onSuccess(200, 'wallet added successfully', $wallet);
            }
            return $this->onSuccess(200, 'error', $wallet);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function decreaseWallet(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:social_users,id',
                'name' => 'required',
                'balance' => 'required',
                'description' => 'nullable',
            ], [], [
                'user_id' => trans('app.user_id'),
                'name' => trans('app.name'),
                'balance' => trans('app.balance'),
                'description' => trans('app.description'),
            ]);
            if ($validator->fails()) {
                $errorString = implode(",", $validator->errors()->all());
                return response([
                    "success" => false,
                    "message" => $errorString
                ], 400);
            }

            $wallet = $this->walletService->decreaseWallet($request);
            if ($wallet){
                return $this->onSuccess(200, 'wallet minus successfully', $wallet);
            }
            return $this->onSuccess(201, 'error', $wallet);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }
}
