<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestsWalletRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|int|exists:users,id',
            'user_image' => 'required|image:jpeg,png,jpg',
            'id_card_image' => 'required|image:jpeg,png,jpg'
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User ID is required',
            'user_id.int' => 'User ID must be an integer',
            'user_id.exists' => 'User ID does not exist',
            'user_image.required' => 'User image is required',
            'user_image.image' => 'User image must be a jpeg, png, or jpg',
            'id_card_image.required' => 'ID card image is required',
            'id_card_image.image' => 'ID card image must be a jpeg, png, or jpg',
        ];
    }
}
