<?php

namespace App\Filters;

use Closure;
use Illuminate\Support\Facades\DB;

class Name extends Filter
{

    public function applyFilter($builder)
    {
        return $builder->where('user.name', 'LIKE', '%' . request($this->filterName()) . '%');
    }
}
