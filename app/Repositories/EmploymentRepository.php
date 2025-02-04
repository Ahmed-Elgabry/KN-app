<?php


namespace App\Repositories;



use App\Models\Employment;

class EmploymentRepository extends BaseRepository
{
    protected $modeler = Employment::class;

    public function getAllData()
    {
        return $this->modeler->with(['image', 'craft', 'city', 'adPlan', 'user'])->get();
    }

    public function getData($id)
    {
        return $this->modeler->where('id', $id)->with(['image', 'craft', 'city', 'adPlan', 'user'])->first();
    }

    public function userEmployment()
    {
        return $this->modeler->where('user_id', auth()->id())->with(['image', 'craft', 'city', 'adPlan', 'user'])->get();
    }

    public function cityEmployment($id)
    {
        return $this->modeler->where('city_id', $id)->with(['image', 'craft', 'city', 'adPlan', 'user'])->get();
    }
}
