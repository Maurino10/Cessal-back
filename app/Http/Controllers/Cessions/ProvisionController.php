<?php

namespace App\Http\Controllers\Cessions;

use App\Services\Cessions\CessionProvisionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProvisionController {

    protected $cessionProvisionService;

    public function __construct(CessionProvisionService $cessionProvisionService) {
        $this->cessionProvisionService = $cessionProvisionService;
    }

    public function storeCessionProvision (Request $request) {
        try {
            $data = $request->validate([
                'provision_amount' => 'required|numeric|min:0',
                'date_provision' => 'required|date',
            ], [
                'provision_amount.required' => 'Le montant de la provision est obligatoire.',
                'provision_amount.numeric' => 'Le montant de la provision doit être un nombre.',
                'provision_amount.min' => 'Le montant de la provision doit être positif.',
                'date_provision.required' => 'La date est obligatoire.',
            ]);

            $provision = $this->cessionProvisionService->saveProvision(
                $data['provision_amount'], 
                $data['date_provision']
            );

            return response()->json([
                "provision" => $provision
            ]);
        } catch (ValidationException $e) {
            return $e;
        }
    }  
    
    public function editCessionProvision ($idCessionProvision, Request $request) {
        try {
            $data = $request->validate([
                'provision_amount' => 'required|numeric|min:0',
                'date_provision' => 'required|date',

            ], [
                'provision_amount.required' => 'Le montant de la provision est obligatoire.',
                'provision_amount.numeric' => 'Le montant de la provision doit être un nombre.',
                'provision_amount.min' => 'Le montant de la provision doit être positif.',
                'date_provision.required' => 'La date est obligatoire.',
            ]);

            $provision = $this->cessionProvisionService->updateProvision(
                $idCessionProvision, 
                $data['provision_amount'],
                $data['date_provision']
            );

            return response()->json([
                "provision" => $provision
            ]);
        } catch (ValidationException $e) {
            return $e;
        }
    }  

    public function getAllCessionProvision () {
        $provisions = $this->cessionProvisionService->findAllProvision();

        return response()->json([
            "provisions" => $provisions
        ]);        
    }

}