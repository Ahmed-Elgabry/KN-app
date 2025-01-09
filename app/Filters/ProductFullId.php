<?php

namespace App\Filters;

use Closure;

class ProductFullId extends Filter
{
    public function applyFilter($builder)
    {
        $ex = explode('-',request($this->filterName()));

        if(isset($ex[1])){  // variant id
            return $builder->where('product.product_id', $ex[0])
                ->where('product_option_variant.product_option_variant_id', $ex[1]);
        }else{
            return $builder->where('product.product_id', $ex[0])
                ->orWhere('product_option_variant.product_option_variant_id', $ex[0]);
        }


    }
}
