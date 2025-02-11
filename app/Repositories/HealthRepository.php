<?php


namespace App\Repositories;



use App\Models\Employment;
use App\Models\Health;

class HealthRepository extends BaseRepository
{
    protected $modeler = Health::class;

    public function getAllData()
    {
        return $this->modeler->with(['image', 'city', 'adPlan', 'user'])->get();
    }

    public function getData($id)
    {
        return $this->modeler->where('id', $id)->with(['image', 'city', 'adPlan', 'user'])->first();
    }
}
