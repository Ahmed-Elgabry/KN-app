<?php

namespace App\Filters;

use App\Models\City;
use Closure;

class StationId extends Filter
{
    public function applyFilter($builder)
    {
        return $builder->whereHas('order_fulfillment_operators', function ($query){
            $query->whereHas('stationOperator', function ($query){
                    $query->where('station_id', request($this->filterName()))->where('status', 1);
                });
        });
    }
}
