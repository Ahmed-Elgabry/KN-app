<?php

namespace App\Filters;

use Closure;

class ShippingCountry extends Filter
{
    public function applyFilter($builder)
    {
        return $builder->where('order.shipping_country','Like','%'.request($this->filterName()).'%');
    }
}
