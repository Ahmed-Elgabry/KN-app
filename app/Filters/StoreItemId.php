<?php

namespace App\Filters;

use Closure;

class StoreItemId extends Filter
{
    public function applyFilter($builder)
    {
        return $builder->where($builder->getModel()->getTable().'.'.$this->filterName(), request($this->filterName()));
    }
}
