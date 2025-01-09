<?php

namespace App\Filters;

use Closure;

class ShippingZone extends Filter
{
    public function applyFilter($builder)
    {
        return $builder->where('order.shipping_zone','Like','%' .request($this->filterName()).'%');
    }
}
