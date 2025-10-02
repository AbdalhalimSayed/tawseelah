<?php

namespace App\Http\Requests\Driver;

use App\Helper\APIResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ContactRequest extends FormRequest
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
            "email" => ["required", "email", "exists:drivers,email"],
            "subject" => ["required", "string", "max:255"],
            "message" => ["required", "string", "max:255"],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->validationError(__("auth.data"), $validator->errors());

        throw new ValidationException($validator, $response);
    }
}
