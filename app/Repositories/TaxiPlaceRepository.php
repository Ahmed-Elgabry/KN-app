<?php


namespace App\Repositories;

use App\Models\TaxiPlace;

class TaxiPlaceRepository extends BaseRepository
{
    protected $modeler = TaxiPlace::class;

    public function deleteData($id)
    {
        return $this->modeler->where('taxi_id', $id)->delete();
    }
}
