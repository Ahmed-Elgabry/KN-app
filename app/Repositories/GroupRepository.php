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

    public function getData($id)
    {
        return $this->modeler::where('id', $id)->with(['owner', 'members', 'groupInterest', 'media'])->first();
    }

    public function getUserData($id)
    {
        return $this->modeler::where('social_user_id', $id)->with(['owner', 'members', 'groupInterest', 'media'])->first();
    }

}
