<?php

namespace App\Filters;

use Closure;

class RegionId extends Filter
{
    public function applyFilter($builder)
    {
        return $builder->where('ci.region_id', request($this->filterName()));
    }
}
