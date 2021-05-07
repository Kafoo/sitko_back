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
    * Prepare the data for validation.
    *
    * @return void
    */
    protected function prepareForValidation()
    {

        if ($this->user_type) {
        $this->merge([
            'user_type_id' => $this->user_type['id'],
        ]);
        }
        if ($this->home_type) {
        $this->merge([
            'home_type_id' => $this->home_type['id'],
        ]);
        }
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
            $nameRules = ['required', 'string', 'max:20'];
        }else{ 
            $nameRules = ['required', 'string', 'max:20', 'unique:users,name,'.auth()->id()];
        }

        return [
            'name' => $nameRules,
            'last_name' => ['max:20'],
            'email' => ['required', 'string', 'email', 'unique:users,email,'.auth()->id()],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'bio' => ['nullable', 'string'],
            'expectations' => ['nullable', 'string'],
            'user_type_id' => ['nullable', 'numeric'],
            'home_type_id' => ['nullable', 'numeric'],
            'contact_infos' => ['nullable', 'array'],
            'contact_infos.facebook' => ['nullable', 'string', 'url', 'regex:/^(http|https)/'],
            'contact_infos.instagram' => ['nullable', 'string', 'url', 'regex:/^(http|https)/'],
            'contact_infos.youtube' => ['nullable', 'string', 'url', 'regex:/^(http|https)/'],
            'contact_infos.email' => ['nullable', 'string', 'email'],
            'preferences' => ['nullable', 'array']
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
