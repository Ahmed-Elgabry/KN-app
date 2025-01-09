<?php

namespace App\Filters;

use Closure;

class OperatorId extends Filter
{
    public function applyFilter($builder)
    {
        return $builder->whereHas('order_operators', function($order_picker){
            $order_picker->where('operator_id',request($this->filterName()));
        });
    }
}
