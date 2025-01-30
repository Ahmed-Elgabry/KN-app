<?php


namespace App\Repositories;



use App\Models\AdPlans;

class AdPlanRepository extends BaseRepository
{
    protected $modeler = AdPlans::class;
}
