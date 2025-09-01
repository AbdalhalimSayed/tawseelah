<?php

namespace App\Http\Requests\User;

use App\Helper\APIResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class CheckOtpRequest extends FormRequest
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
            "email" => ["required", "email", "exists:password_resets,email"],
            "code" => ["required", "exists:password_resets,code", "max:5"],
        ];
    }

    public function attributes(){
        return [
            "code" => __("validation.otp"),
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $response = $this->validationError(__("auth.data"), $validator->errors());
        throw new ValidationException($validator, $response);
    }
}
