<?php

namespace App\Http\Requests\User;

use App\Helper\APIResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    Use APIResponse;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
                "name"=>["required", "max:20", "string"],
                "email"=>["required", "email", "unique:users,email"],
                "password"=>["required", "min:8", "confirmed"]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response=$this->validationError(__("auth.data"), [$validator->errors()->keys()[0]=>$validator->errors()->first()]);
        throw new ValidationException($validator, $response);
    }
}
