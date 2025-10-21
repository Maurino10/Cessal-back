<?php

namespace App\Http\Requests\Territories;

use Illuminate\Foundation\Http\FormRequest;

class DistrictRequest extends FormRequest
{
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
            "name"=> "required",
            "province" => "required|exists:province,id",
            "region" => "required|exists:region,id",
        ];
    }

    public function messages(): array {
        return [
            "name.required"=> "Le nom est obligatoire",
            "province.required"=> "Une province doit être sélectionnée",
            "province.exists"=> "La province sélectionnée n'existe pas",
            "region.required"=> "Une region doit être sélectionnée",
            "region.exists"=> "La region sélectionnée n'existe pas",
        ];
    }
}
