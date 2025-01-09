<?php


namespace App\Repositories;



use App\Models\bill;
use App\Models\Operator;
use App\Models\Type;
use App\Repositories\BaseRepository;

class BillRepository extends BaseRepository
{
    protected $modeler = bill::class;

    public function getDataTableQuery()
    {
       return $this->modeler->select([
          'id',
          'operator_id',
          'created_at',
          'updated_at',
       ]);
    }
}
