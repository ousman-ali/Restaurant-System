<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployee extends FormRequest
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
            'name'      =>      'required|max:50',
            'role'      =>      'required|max:1',
            'address'   =>      'required|min:10',
            'phone'     =>      'required',
            'thumbnail' =>      'image'
        ];
    }
}
