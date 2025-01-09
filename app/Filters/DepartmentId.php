<?php


namespace App\Filters;


use Illuminate\Support\Facades\DB;

class DepartmentId extends Filter
{
    public function applyFilter($builder)
    {
        return $builder->where($builder->getModel()->getTable().'.'.$this->filterName(), request($this->filterName()));
    }
}
