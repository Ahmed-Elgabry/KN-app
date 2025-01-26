<?php

namespace App\Services\Apis;


use App\Repositories\SubWalletRepository;
use App\Repositories\WalletRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class WalletService extends BaseService
{
    protected $walletRepository;
    protected $subWalletRepository;

    public function __construct(WalletRepository $walletRepository, SubWalletRepository $subWalletRepository)
    {
        parent::__construct();
        $this->walletRepository = $walletRepository;
        $this->subWalletRepository = $subWalletRepository;
    }

    public function createWallet($request)
    {
        DB::beginTransaction();
        try {
            $wallet = $this->walletRepository->getFirstWhere(['user_id' => $request->user_id]);
            if (!$wallet) {
                $wallet = $this->walletRepository->store([
                    'user_id' => $request->user_id,
                    'name' => $request->name,
                    'total' => 0,
                ]);
            }else{
                return false;
            }
            DB::commit();
            return $this->walletRepository->find($wallet->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }
    public function increaseWallet($request)
    {
        DB::beginTransaction();
        try {
            $wallet = $this->walletRepository->getFirstWhere(['user_id' => $request->user_id]);
            if ($wallet){
                $wallet->total = $wallet->total + $request->balance;
                $wallet->save();
            }else{
                $wallet = $this->walletRepository->store([
                    'user_id' => $request->user_id,
                    'name' => $request->name,
                    'total' => $request->balance,
                ]);
            }

           $subWallet = $this->subWalletRepository->store([
                'wallet_id' => $wallet->id,
                'balance' => $request->balance,
                'operation' => 'plus',
                'description' => $request->description,
            ]);

            DB::commit();
            return $this->walletRepository->find($wallet->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }
    public function decreaseWallet($request)
    {
        DB::beginTransaction();
        try {
            $wallet = $this->walletRepository->getFirstWhere(['user_id' => $request->user_id]);
            if ($wallet){
                if ($wallet->total >= $request->balance){
                    $wallet->total = $wallet->total - $request->balance;
                    $wallet->save();

                    $this->subWalletRepository->store([
                        'wallet_id' => $wallet->id,
                        'balance' => $request->balance,
                        'operation' => 'minus',
                        'description' => $request->description,
                    ]);
                }else{
                    return false;
                }
            }else{
                return false;
            }
            DB::commit();
            return $this->walletRepository->find($wallet->id);
        } catch (\Exception $e) {
            DB::rollback();
            errorLog($e->getMessage());
            return false;
        }
    }

}
