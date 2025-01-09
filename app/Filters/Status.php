<?php

namespace App\Filters;

use Closure;

class Status extends Filter
{
    public function applyFilter($builder)
    {
        $status = request($this->filterName()) =='-1'?'0':request($this->filterName());
 
        return $builder->where($builder->getModel()->getTable().'.'.$this->filterName(), $status);
    }
}
