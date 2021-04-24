<?php

namespace App\Http\Requests;



class UserRequest extends GlobalRequest
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

        //First Name will be considered as Alias if no Last Name is provided.
        //If so, Alias has to be unique 
        if ($this->last_name) {
            $nameRules = ['required', 'string', 'max:255'];
        }else{ 
            $nameRules = ['required', 'string', 'max:255', 'unique:users,name,'.auth()->id()];
        }

        return [
            'name' => $nameRules,
            'last_name' => ['max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.auth()->id()],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];

        return [
            //
        ];
    }

/**
 * Get the error messages for the defined validation rules.
 *
 * @return array
 */
public function messages()
{
    return [
        'name.unique' => trans('validation.custom.unique.name', ['input' => $this->name]),
        'email.unique' => trans('validation.custom.unique.email', ['input' => $this->email])
    ];
}

}
