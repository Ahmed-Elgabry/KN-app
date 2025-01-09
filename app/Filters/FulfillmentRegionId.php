<?php

namespace App\Filters;

use Closure;

class FulfillmentRegionId extends Filter
{
    public function applyFilter($builder)
    {
        return $builder->whereHas('order', function ($query){
            $query->whereHas('city', function ($query){
               $query->where('region_id', request($this->filterName()));
            });
        });
    }
}
