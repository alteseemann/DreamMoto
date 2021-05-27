<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SaveSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'images'         => 'nullable',
            'images.image.*' => 'mimes:jpeg,png',
            'price'          => 'required|integer|min:1',
            'description'    => 'nullable|string',
            'mileage'        => 'nullable|integer|min:0',
            'year'           => 'required|integer|min:1901|max:' . date("Y"),
        ];
    }
}
