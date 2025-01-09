<?php

namespace App\Filters;

use Closure;
use Illuminate\Support\Facades\DB;

class ProductFullName extends Filter
{

    public function applyFilter($builder)
    {
        return $builder->whereHas('descriptionWithOutLang', function ($q) {
            $q->where('product_description.name', 'LIKE', '%' . request($this->filterName()) . '%');
        })
            ->Orwhere('option_value_description.name', 'LIKE', '%' . request($this->filterName()) . '%');
    }
}
