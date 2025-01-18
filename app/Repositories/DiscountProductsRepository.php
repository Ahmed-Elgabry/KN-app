<?php


namespace App\Repositories;



use App\Models\Discount;
use App\Models\DiscountProduct;
use App\Repositories\BaseRepository;

class DiscountProductsRepository extends BaseRepository
{
    protected $modeler = DiscountProduct::class;

}
