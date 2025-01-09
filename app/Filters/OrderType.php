<?php

namespace App\Filters;

use Closure;

class OrderType extends Filter
{
    public function applyFilter($builder)
    {
        if(request($this->filterName()) == 'multi'){
            return $builder->has('order_items', '>', 1);
        }elseif(request($this->filterName()) == 'single'){
            return $builder->has('order_items','=', 1);
        }

        return $builder;
    }
}
