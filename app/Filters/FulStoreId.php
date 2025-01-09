<?php

namespace App\Filters;

use Closure;

class FulStoreId extends Filter
{
    public function applyFilter($builder)
    {
        return $builder->whereHas('order', function ($order) {
            $order->where('order.store_id', request($this->filterName()));
        });
    }
}
