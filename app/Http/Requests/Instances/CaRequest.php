<?php

namespace App\Http\Requests\Instances;

use Illuminate\Foundation\Http\FormRequest;

class CaRequest extends FormRequest
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
            "province" => "required|exists:province,id"
        ];
    }

    public function messages(): array {
        return [
            "name.required"=> "Le nom est obligatoire",
            "province.required"=> "Une province doit être sélectionnée",
            "province.exists"=> "La province sélectionnée n'existe pas",
        ];
    }
}
