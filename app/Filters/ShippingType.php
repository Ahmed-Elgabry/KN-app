<?php

namespace App\Filters;

use Closure;

class ShippingType extends Filter
{
    public function applyFilter($builder)
    {
        if(request($this->filterName()) == 'international'){
            return $builder->where('shipping_country', '!=', 'SA');
        }elseif(request($this->filterName()) == 'domestic'){
            return $builder->where('shipping_country', '=', 'SA');
        }

        return $builder;
    }
}
