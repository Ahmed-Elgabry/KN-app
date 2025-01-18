<?php


namespace App\Repositories;



use App\Models\Discount;
use App\Models\DiscountProduct;
use App\Models\DiscountTime;
use App\Repositories\BaseRepository;

class DiscountTimesRepository extends BaseRepository
{
    protected $modeler = DiscountTime::class;

}
