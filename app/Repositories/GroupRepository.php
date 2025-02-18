<?php

namespace App\Repositories;

use App\Models\Group;
use App\Models\Wallet;

class GroupRepository extends BaseRepository
{
    protected $modeler = Group::class;

    public function getAllData()
    {
        return $this->modeler::with(['owner' , 'members' , 'groupInterest' , 'media'])->get();
    }

    public function deductBalance($userId, $amount)
    {
        $wallet = Wallet::where('user_id', $userId)->first();

        if ($wallet && $wallet->wallet_balance >= $amount) {
            $wallet->wallet_balance -= $amount;
            $wallet->save();
            return true;
        }

        return false;
    }
}
