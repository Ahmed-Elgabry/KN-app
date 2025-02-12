<?php


namespace App\Repositories;

use App\Models\TaxiRequest;

class TaxiRequestRepository extends BaseRepository
{
    protected $modeler = TaxiRequest::class;
}
