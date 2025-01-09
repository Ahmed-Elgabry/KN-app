<?php

namespace App\Filters;

use Closure;
use Illuminate\Support\Facades\DB;

class ItemName extends Filter
{

    public function applyFilter($builder)
    {
        return $builder->where(function($q) {
            $q->where('item.name_ar', 'LIKE', '%' . request($this->filterName()) . '%')->orwhere('item.name_en', 'LIKE', '%' . request($this->filterName()) . '%');
        });
    }
}
