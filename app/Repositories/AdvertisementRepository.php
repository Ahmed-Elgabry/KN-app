<?php

namespace App\Repositories;

use App\Models\Advertisement;

class AdvertisementRepository extends BaseRepository
{
    protected $modeler = Advertisement::class;

    public function getAllData()
    {
        return $this->modeler->with('advertisementProducts')->get();
    }

    public function getData($id)
    {
        return $this->modeler->where('id', $id)->with('advertisementProducts')->first();
    }
}
