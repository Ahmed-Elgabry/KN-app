<?php

namespace App\Filters;

use Closure;

class OrderDateTo extends Filter
{

    public function applyFilter($builder)
    {
        return $builder->where('order_date','<=', request($this->filterName()));
    }
}
