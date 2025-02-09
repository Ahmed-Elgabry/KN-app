<?php


namespace App\Repositories;



use App\Models\CityMarket;

class CityMarketRepository extends BaseRepository
{
    protected $modeler = CityMarket::class;

    public function getAllData()
    {
        return $this->modeler->with(['image', 'city', 'adPlan', 'user'])->get();
    }

    public function getData($id)
    {
        return $this->modeler->where('id', $id)->with(['image', 'city', 'adPlan', 'user'])->first();
    }

}
