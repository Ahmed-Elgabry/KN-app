<?php

namespace App\Filters;

use Closure;

class ShippingCity extends Filter
{
    public function applyFilter($builder)
    {
        return $builder->where('order.shipping_city','Like','%'.request($this->filterName()).'%');
    }
}
