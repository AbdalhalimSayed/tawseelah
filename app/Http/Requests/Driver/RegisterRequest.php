<?php

namespace App\Http\Requests\Driver;

use App\Helper\APIResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
{
    use APIResponse;
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
            "name" => ["required", "string", "max:25", "min:5"],
            "email" => ["required", "email", "unique:drivers,email"],
            "phone" => ["required", "string", "min:11", "max:11", "unique:drivers,phone", "regex:/^(011|012|015|010)\d{8}$/"],
            "password" => ["required", "string", "min:8", "confirmed"],
        ];

    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->validationError(__("auth.data"), $validator->errors());

        throw new ValidationException($validator, $response);
    }
}
