<?php


namespace App\Repositories;

use App\Models\Taxi;

class TaxiRepository extends BaseRepository
{
    protected $modeler = Taxi::class;

    public function getAllData()
    {
        return $this->modeler->with(['city', 'places', 'adPlan', 'user'])->get();
    }

    public function getData($id)
    {
        return $this->modeler->where('id', $id)->with(['city', 'places', 'adPlan', 'user'])->first();
    }
}
