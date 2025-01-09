<?php

namespace App\Filters;

class OperatorType extends Filter
{
    public function applyFilter($builder)
    {
        return $builder->where('operator_type.id', request($this->filterName()));
    }
}
