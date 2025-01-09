<?php

namespace App\Filters;

use Closure;

class ReferenceType extends Filter
{
    public function applyFilter($builder)
    {
        return $builder->where('order.reference_type', request($this->filterName()));
    }
}
