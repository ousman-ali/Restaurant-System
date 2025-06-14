<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class SaveEmployee extends FormRequest
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
            'email'     =>      'required|unique:users|max:50',
            'password'  =>      'required|min:5|max:20',
            'role'      =>      'required|max:1',
            'address'   =>      'required|min:10',
            'phone'     =>      'required',
            'thumbnail' =>      'image'
        ];
    }
}
