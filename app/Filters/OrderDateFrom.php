<?php

namespace App\Filters;

use Closure;

class OrderDateFrom extends Filter
{

    public function applyFilter($builder)
    {
        return $builder->whereDate('order.order_date','>=', date('Y-m-d H:i:s',strtotime(request($this->filterName()))));
    }
}
