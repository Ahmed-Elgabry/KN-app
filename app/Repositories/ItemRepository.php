<?php


namespace App\Repositories;



use App\Models\Item;

class ItemRepository extends BaseRepository
{
    protected $modeler = Item::class;
}
