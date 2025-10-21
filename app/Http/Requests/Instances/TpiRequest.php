<?php

namespace App\Http\Requests\Instances;

use Illuminate\Foundation\Http\FormRequest;

class TpiRequest extends FormRequest
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
            "ca" => "required|exists:ca,id",
            "region" => "required|exists:region,id",
            "district" => "required|exists:district,id",
        ];
    }

    public function messages(): array {
        return [
            "name.required"=> "Le nom est obligatoire",
            "province.required"=> "Une province doit être sélectionnée",
            "province.exists"=> "La province sélectionnée n'existe pas",
            "ca.required"=> "Une cour d'appel doit être sélectionnée",
            "ca.exists"=> "La cour d'appel sélectionnée n'existe pas",
            "region.required"=> "Une region doit être sélectionné",
            "region.exists"=> "La region sélectionné n'existe pas",
            "district.required"=> "Un district doit être sélectionné",
            "district.exists"=> "Le district sélectionné n'existe pas",
        ];
    }
}
