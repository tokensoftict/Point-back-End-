<?php

namespace Modules\Auth\Http\Requests;

use App\Traits\RespondsWithHttpStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserLoginRequest extends FormRequest
{
    use RespondsWithHttpStatus;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
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


        $v_errors =  $validator->errors()->toArray();

        $message = [];

        foreach ($v_errors as $key => $errors)
        {
            foreach ($errors as $error)
            {
                $message[] = $error;
            }

        }

        throw new HttpResponseException($this->failure(implode("<br/>",$message),$v_errors));
    }


    public function messages()
    {
        return [
            "email.required" => "Please enter user's email address!",
            "email.email" => "Please enter a valid email!",
            "password.required" => "Please enter user's password!",
        ];
    }
}
