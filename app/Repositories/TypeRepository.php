<?php


namespace App\Repositories;



use App\Models\Operator;
use App\Models\Type;
use App\Repositories\BaseRepository;

class TypeRepository extends BaseRepository
{
    protected $modeler = Type::class;

    public function getDataTableQuery()
    {
       return $this->modeler->select([
          'id',
          'name',
          'price',
          'created_at',
       ]);
    }
}
