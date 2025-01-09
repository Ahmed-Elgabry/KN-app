<?php

namespace App\Filters;

use Closure;

class OrderStatusId extends Filter
{
    public function applyFilter($builder)
    {
        if(request($this->filterName()) && isset(request($this->filterName())[0]) && !is_null(request($this->filterName())[0]) && !in_array(request($this->filterName())[0],['all','accepted']) ){
            return $builder->whereIn('order.order_status_id', request($this->filterName()));
        }elseif(request($this->filterName()) && isset(request($this->filterName())[0]) && !is_null(request($this->filterName())[0]) && request($this->filterName())[0] == 'all'){
            return $builder;
        }elseif(request($this->filterName()) && isset(request($this->filterName())[0]) && !is_null(request($this->filterName())[0]) && request($this->filterName())[0] == 'accepted'){
            return $builder->Accepted();
        }
    }
}
