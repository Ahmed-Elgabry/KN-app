<?php


namespace App\Repositories;



use App\Models\Gift;

class GiftsRepository extends BaseRepository
{
    protected $modeler = Gift::class;

    public function gifts()
    {
        return $this->modeler->select(['id', 'name_en', 'name_ar', 'value'])->with('image')->get();
    }

    public function gift($id)
    {
        return $this->modeler->select(['id', 'name_en', 'name_ar', 'value'])->where('id', $id)->with('image')->first();
    }

}
