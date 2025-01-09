<?php

namespace App\Filters;

use Closure;

class ItemId extends Filter
{
    public function applyFilter($builder)
    {
        return $builder->where('item.id', request($this->filterName()));
    }
}
