<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'min:3', 'max:60'],
            'category_id' => ['required'],
            'cost_price' => ['required', 'numeric', 'min:0.01', 'max:999.99'],
            'sale_price' => ['required', 'numeric', 'min:0.01', 'max:999.99'],
            'units_stock' => ['required', 'min:1', 'max:999'],
            'active' => ['required'],
            'photo' => ['image', 'max:50', 'dimensions:max_width=150,max_height=150,min_width=50,min_height=50'],
        ];
    }
}
