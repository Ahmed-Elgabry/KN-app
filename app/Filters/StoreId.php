<?php

namespace App\Filters;

use Closure;

class StoreId extends Filter
{
    public function applyFilter($builder)
    {
        return $builder->where('order.store_id', request($this->filterName()));
    }
}
