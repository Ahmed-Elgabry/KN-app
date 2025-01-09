<?php

namespace App\Filters;

use Closure;

class FulfillmentShippingType extends Filter
{
    public function applyFilter($builder)
    {
        if(request($this->filterName()) == 'international'){
            return $builder->whereHas('order', function ($query){
                $query->where('shipping_country', '!=', 'SA');
            });
        }elseif(request($this->filterName()) == 'domestic'){
            $builder->whereHas('order', function ($query){
                $query->where('shipping_country', '=', 'SA');
            });
        }

        return $builder;
    }
}
