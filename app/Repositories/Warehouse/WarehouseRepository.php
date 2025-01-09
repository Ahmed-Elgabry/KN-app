<?php


namespace App\Repositories\Warehouse;


use App\Models\Warehouse;
use App\Repositories\BaseRepository;


class WarehouseRepository extends BaseRepository
{
    protected $modeler = Warehouse::class;

    public function getDataTableQuery()
    {
        return $this->modeler->select(['id','name']);
    }
}
