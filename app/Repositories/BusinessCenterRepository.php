<?php


namespace App\Repositories;



use App\Models\BusinessCenter;

class BusinessCenterRepository extends BaseRepository
{
    protected $modeler = BusinessCenter::class;

    public function getAllData()
    {
        return $this->modeler->with(['image', 'city', 'adPlan', 'user'])->get();
    }

    public function getData($id)
    {
        return $this->modeler->where('id', $id)->with(['image', 'city', 'adPlan', 'user'])->first();
    }

    public function userBusinessCenter()
    {
        return $this->modeler->where('user_id', auth()->id())->with(['image', 'city', 'adPlan', 'user'])->get();
    }

    public function cityBusinessCenter($id)
    {
        return $this->modeler->where('city_id', $id)->with(['image', 'city', 'adPlan', 'user'])->get();
    }
}
