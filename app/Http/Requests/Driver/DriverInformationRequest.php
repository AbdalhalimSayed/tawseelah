<?php

namespace App\Http\Requests\Driver;

use App\Helper\APIResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class DriverInformationRequest extends FormRequest
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
            "card_number" => ["required", "string", "min:14", "max:14"],
            "country"     => ["required", "string"],
            "state"       => ["required", "string"],
            "city"        => ["required", "string"],
            "license"     => ["required", "image", "mimes:jpeg,png,jpg,gif,svg", "max:2048"],
            "id_card"     => ["required", "image", "mimes:jpeg,png,jpg,gif,svg", "max:2048"],
            "image"       => ["required", "image", "max:2048", "mimes:jpeg,png,jpg,gif,svg"],
        ];
    }

    public function attributes(){
        return [
            "license"   => __("actions.user_license"),
            "id_card"   => __("actions.id_card"),
            "image"     => __("actions.user_image"),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->validationError( __("auth.data"), $validator->errors());

        throw new ValidationException($validator, $response);
    }
}
