<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRegistrationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'   => false,
            'message'   => 'Data Validation errors',
            'data'      => $validator->errors()
        ]));
    }

    public function messages()
    {
        return [
            "name.required" => "Please enter user's full name!",
            "email.required" => "Please enter user's email address!",
            "email.email" => "Please enter a valid email!",
            "email.unique" => "Email Address has already been used!",
            "password.required" => "Please enter user's password!",
        ];
    }

}
