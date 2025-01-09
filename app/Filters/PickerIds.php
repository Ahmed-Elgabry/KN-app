<?php

namespace App\Filters;

use Closure;

class PickerIds extends Filter
{
    public function applyFilter($builder)
    {
        return $builder->whereHas('order_fulfillment_operators', function($order_picker){
            $order_picker->whereIn('operator_id',request($this->filterName()));
        });
    }
}
