<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'first_name' => 'required|string|max:191|unique:users,first_name,null,id,last_name,'
                .$this->last_name.','.'patronymic,'.$this->patronymic,
            'last_name' => 'required|string|max:191|unique:users,last_name,null,id,first_name,'
                .$this->first_name.','.'patronymic,'.$this->patronymic,
            'patronymic' => 'required|string|max:191|unique:users,patronymic,null,id,last_name,'
                .$this->last_name.','.'first_name,'.$this->first_name,
            'phone' => 'required|numeric',
            'email' => 'required|email|unique:users,email',
            // 'password' => 'required|min:6'
        ];
    }

    public function messages(){
        return [
            'first_name.unique' => 'Full Name must be unique',
            'last_name.unique' => 'Full Name must be unique',
            'patronymic.unique' => 'Full Name must be unique',
        ];
    }
}
