<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class OperatorFormRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
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
                    'employee_id' => 'nullable|unique:operator,employee_id|numeric',
                    'name' => 'required|string|max:300',
                    'operator_type_id' => 'required',
                    'status' => 'in:1,0',
                ];

            }
            case 'PUT':
            case 'PATCH':
        {
            return [];
        }

            default:break;
        }

    }

    public function attributes()
    {
        $messages['operator_type_id'] = __('type');

        return $messages;
    }


}
