<?php


namespace App\Repositories;



use App\Models\Discount;

class DiscountRepository extends BaseRepository
{
    protected $modeler = Discount::class;

    public function getData($id)
    {
        return $this->modeler->where('id', $id)->with('mainImage')
            ->with('imageList')->with('products')->with('times')->first();
    }

    public function getAllData()
    {
        return $this->modeler->with('mainImage')
            ->with('imageList')->with('products')->with('times')->get();
    }

    public function userDiscounts()
    {
        return $this->modeler->where('user_id', auth()->id())->with('mainImage')
            ->with('imageList')->with('products')->with('times')->get();
    }
}
