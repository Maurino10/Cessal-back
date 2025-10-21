<?php

namespace App\Http\Controllers\Cessions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cessions\CessionLenderRequest;
use App\Models\Cessions\Cession;
use App\Services\Cessions\CessionPersonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class LenderController extends Controller {
    protected $cessionPersonService;

    public function __construct(CessionPersonService $cessionPersonService) {
        $this->cessionPersonService = $cessionPersonService;
    }

    public function storeCessionLender ($idCession, CessionLenderRequest $request) {
        
        $cession = Cession::findOrFail($idCession);

        $this->authorize('store', $cession);
        
        $data = $request->validated();

        $lender = null;

        if ($data['type'] === 'person') {
            $lender = $this->cessionPersonService->saveCessionLender(
                $data['last_name'], 
                $data['first_name'], 
                $data['cin'],
                $data['gender'],
                $idCession
            );
        } else {
            $lender = $this->cessionPersonService->saveCessionLenderEntity(
                $data['name'],
                $data['address'],
                $data['tpi'],
                $idCession
            );
        }


        return response()->json([
            'lender' => $lender
        ]);
    }

    public function storeCessionLenderExists ($idCession, Request $request) {
        try {
            $cession = Cession::findOrFail($idCession);
    
            $this->authorize('store', $cession);
            
            $data = $request->validate([
                'party' => 'required|numeric'
            ]);
            
            $lender = $this->cessionPersonService->saveCessionLenderExists(
                $idCession,
                $data['party'],
            );
    
    
            return response()->json([
                'lender' => $lender
            ]);
        } catch (ValidationException $ve) {
            return response()->json([
                'errors' => $ve->errors()
            ], 422);
        }
    }

    public function storeCessionLenderExistsNewAddress ($idCession, Request $request) {
        try {
            $cession = Cession::findOrFail($idCession);
    
            $this->authorize('store', $cession);
            
            $data = $request->validate([
                'party' => 'required|numeric',
                'address' => 'required|string'
            ], [
                'address.required' => 'L’adresse est obligatoire.',
                'address.string' => 'L’adresse doit être une chaîne de caractères.',
            ]);
            
            $lender = $this->cessionPersonService->saveCessionLenderExists(
                $idCession,
                $data['party'],
            );
    
            $address = $this->cessionPersonService->saveCessionPartyAddress(
                $data['address'],
                $data['party']
            );

            return response()->json([
                'lender' => $lender
            ]);
        } catch (ValidationException $ve) {
            return response()->json([
                'errors' => $ve->errors()
            ], 422);
        }
    }

    public function storeCessionLenderEntityExists ($idCession, Request $request) {
        try {
            $cession = Cession::findOrFail($idCession);
    
            $this->authorize('store', $cession);
            
            $data = $request->validate([
                'party' => 'required|numeric'
            ]);
            
            $lender = $this->cessionPersonService->saveCessionLenderEntityExists(
                $idCession,
                $data['party'],
            );
    
    
            return response()->json([
                'lender' => $lender
            ]);
        } catch (ValidationException $ve) {
            return response()->json([
                'errors' => $ve->errors()
            ], 422);
        }
    }

    public function editCessionLender ($idCession, $idCessionLender, CessionLenderRequest $request) {
        $cession = Cession::findOrFail($idCession);

        $this->authorize('update', $cession);

        $data = $request->validated();

        $lender = null;

        if ($data['type'] === 'person') {
            $lender = $this->cessionPersonService->updateCesssionLender(
                $idCessionLender,
                $data['last_name'], 
                $data['first_name'], 
                $data['address'], 
                $data['cin'],
                $data['gender'],
            );
        } else {
            $lender = $this->cessionPersonService->updateCessionLenderEntity(
                $idCessionLender,
                $data['name'], 
                $data['address'], 
            );
        }

        return response()->json([
            'lender' => $lender
        ]);
    }

    public function removeCessionLender($idCession, $idCessionLender) {

        
        $cession = Cession::findOrFail($idCession);

        $this->authorize('delete', $cession);
        
        $this->cessionPersonService->deleteCessionLender($idCessionLender);

        return response()->json([
            'message' => 'Demandeur supprimé avec succés'
        ]);
    }

    public function getAllCessionLenderByCession($idCession) {
        $cession = Cession::findOrFail($idCession);

        $this->authorize('view', $cession);

        $lenders = $this->cessionPersonService->findAllCessionLenderByCession($idCession);

        return response()->json([
            'lenders' => $lenders
        ]);
    }
}   