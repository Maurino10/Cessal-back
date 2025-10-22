<?php

namespace App\Http\Controllers\Cessions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cessions\CessionLenderRequest;
use App\Models\Cessions\Cession;
use App\Services\Cessions\CessionLenderService;
use App\Services\Cessions\CessionNaturalPersonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class LenderController extends Controller {
    protected $cessionLenderService;

    protected $cessionNaturalPersonService;

    public function __construct(CessionLenderService $cessionLenderService, CessionNaturalPersonService $cessionNaturalPersonService) {
        $this->cessionLenderService = $cessionLenderService;
        $this->cessionNaturalPersonService = $cessionNaturalPersonService;
    }

    public function storeCessionLender ($idCession, CessionLenderRequest $request) {
        
        $cession = Cession::findOrFail($idCession);

        $this->authorize('store', $cession);
        
        $data = $request->validated();

        $lender = null;

        if ($data['type'] === 'natural_person') {
            $lender = $this->cessionLenderService->saveCessionLenderNaturalPerson(
                $data['last_name'], 
                $data['first_name'], 
                $data['cin'],
                $data['address'],
                $data['gender'],
                $idCession
            );
        } else {
            $lender = $this->cessionLenderService->saveCessionLenderLegalPerson(
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
                'natural_person' => 'required|numeric',
                'natural_person_address' => 'required|numeric',
            ]);
            
            $lender = $this->cessionLenderService->saveCessionLenderNaturalPersonExists(
                $idCession,
                $data['natural_person'],
                $data['natural_person_address'],
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
                'natural_person' => 'required|numeric',
                'address' => 'required|string'
            ], [
                'address.required' => 'L’adresse est obligatoire.',
                'address.string' => 'L’adresse doit être une chaîne de caractères.',
            ]);
            
            $lender = $this->cessionLenderService->saveCessionLenderNaturalPersonExistsNewAddress(
                $idCession,
                $data['natural_person'],
                $data['address']
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

    public function storeCessionLenderLegalPersonExists ($idCession, Request $request) {
        try {
            $cession = Cession::findOrFail($idCession);
    
            $this->authorize('store', $cession);
            
            $data = $request->validate([
                'natural_person' => 'required|numeric'
            ]);
            
            $lender = $this->cessionLenderService->saveCessionLenderLegalPersonExists(
                $idCession,
                $data['natural_person'],
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

        if ($data['type'] === 'natural_person') {
            $lender = $this->cessionLenderService->updateCesssionLenderNaturalPerson(
                $idCessionLender,
                $data['last_name'], 
                $data['first_name'], 
                $data['cin'],
                $data['gender'],
                $data['address'], 
            );
        } else {
            $lender = $this->cessionLenderService->updateCessionLenderLegalPerson(
                $idCessionLender,
                $data['name'], 
                $data['address'], 
            );
        }

        return response()->json([
            'lender' => $lender
        ]);
    }

    public function editCessionLenderNewAddress ($idCession, $idCessionLender, CessionLenderRequest $request) {
        $cession = Cession::findOrFail($idCession);

        $this->authorize('update', $cession);

        $data = $request->validated();

        $lender = null;

        $lender = $this->cessionLenderService->updateCesssionLenderNaturalPersonNewAddress(
            $idCessionLender,
            $data['last_name'], 
            $data['first_name'], 
            $data['cin'],
            $data['address'], 
            $data['gender'],
        );

        return response()->json([
            'lender' => $lender
        ]);
    }

    public function removeCessionLender($idCession, $idCessionLender) {

        
        $cession = Cession::findOrFail($idCession);

        $this->authorize('delete', $cession);
        
        $this->cessionLenderService->deleteCessionLender($idCessionLender);

        return response()->json([
            'message' => 'Demandeur supprimé avec succés'
        ]);
    }

    public function getAllCessionLenderByCession($idCession) {
        $cession = Cession::findOrFail($idCession);

        $this->authorize('view', $cession);

        $lenders = $this->cessionLenderService->findAllCessionLenderByCession($idCession);

        return response()->json([
            'lenders' => $lenders
        ]);
    }
}   