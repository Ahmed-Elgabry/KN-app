<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\HelperApi;
use App\Http\Traits\ImageProcessing;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    use HelperApi, ImageProcessing;

    /*public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }*/

    public function index()
    {
        try{
            $cities = City::all();
            return $this->onSuccess(200, 'Cities data filled successfully', $cities);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }

    }

    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required|string',
            'name_ar' => 'required|string',
            'image' => 'required',
        ]);

        try{
            if ($request->image) {
                $imageName = time() . '.' . request()->image->getClientOriginalExtension();
                request()->image->move(public_path('images/cities'), $imageName);
                $city = City::create([
                    'name_en' => $request->name_en,
                    'name_ar' => $request->name_en,
                    'image' => $imageName,
                ]);
            }
            return $this->onSuccess(200, 'City added successfully', $city);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }
    public function update(Request $request)
    {
        $request->validate([
            'name_en' => 'required|string',
            'name_ar' => 'required|string',
            'image' => 'required',
            'id' => 'required',
        ]);

        try{
            if ($request->image) {
                $imageName = time() . '.' . request()->image->getClientOriginalExtension();
                request()->image->move(public_path('images/cities'), $imageName);
                $city = City::find($request->id);
                $city->update([
                    'name_en' => $request->name_en,
                    'name_ar' => $request->name_en,
                    'image' => $imageName,
                ]);
            }
            return $this->onSuccess(200, 'City updated successfully', $city);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            City::where('id', $request->id)->delete();
            return $this->onSuccess(200, 'City deleted');
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }

    }



}
