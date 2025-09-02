<?php

namespace App\Http\Requests\Driver;

use App\Helper\APIResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class CarInformationRequest extends FormRequest
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
            "brand"     => ["required", "string"],
            "model"     => ["required", "string"],
            "color"     => ["required", "string"],
            "year"      => ["required", "integer"],
            "license"   => ["required", "image", "mimes:jpeg,png,jpg,gif,svg", "max:2048"],
            "image"     => ["required", "mimes:jpeg,png,jpg,gif,svg", "max:2048"],
        ];
    }

    public function attributes()
    {
        return [
            "brand"     =>  __("actions.brand"),
            "model"     =>  __("actions.model"),
            "license"   =>  __("actions.license"),
            "image"     =>  __("actions.image-car"),
            "year"      =>  __("actions.year-car"),
            "color"     =>  __("actions.color-car"),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->validationError(__("auth.data"), $validator->errors());
        throw new ValidationException($validator, $response);
    }
}
