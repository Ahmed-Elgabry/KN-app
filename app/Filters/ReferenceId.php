<?php

namespace App\Filters;

use App\Models\City;
use Closure;

class ReferenceId extends Filter
{
    public function applyFilter($builder)
    {
        return $builder->where('order.store_order_id','like','%'. request($this->filterName()).'%');
    }
}
