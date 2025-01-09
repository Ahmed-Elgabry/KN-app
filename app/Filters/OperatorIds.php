<?php

namespace App\Filters;

use Closure;

class OperatorIds extends Filter
{
    public function applyFilter($builder)
    {
        return $builder->whereHas('order_operators', function($order_picker){
            $order_picker->whereIn('operator_id',request($this->filterName()));
        });
    }
}
