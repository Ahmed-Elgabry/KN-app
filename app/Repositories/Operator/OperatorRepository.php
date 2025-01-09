<?php


namespace App\Repositories\Operator;



use App\Models\Operator;
use App\Repositories\BaseRepository;

class OperatorRepository extends BaseRepository
{
    protected $modeler = Operator::class;

    public function getDataTableQuery()
    {
       return $this->modeler->select([
          'id',
          'name',
          'phone',
          'created_at',
       ]);
    }
}
