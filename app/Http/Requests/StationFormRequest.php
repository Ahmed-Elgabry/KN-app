<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StationFormRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->route('station')) {
            $station_id = $this->route('station');
        }
        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'name' => 'required|string|unique:station,name',
                    'max_order_count' => 'required|numeric',
                    'warehouse_id' => 'required',
                    'status' => 'in:1,0',
                ];

            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name' => 'required|string|unique:station,name,'.$station_id.',id',
                    'max_order_count' => 'required|numeric',
                    'status' => 'in:1,0',
                ];
            }

            default:break;
        }

    }

    public function attributes()
    {
        $messages['warehouse_id'] = __('Warehouse');
        $messages['max_order_count'] = __('Max Order Count');

        return $messages;
    }


}
