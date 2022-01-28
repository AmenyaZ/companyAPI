<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
            //
            'email' => 'email|required',
            'password' => 'required'
        ];
    }
    public function messages()
    {
        return [
            "email.required"=>"Please provide email",
            "password.required"=>"please provide password"


        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $response = new Response(["error"=>$validator->errors()->first()],422);
        throw new  ValidationException($validator,$response);
    }
}
