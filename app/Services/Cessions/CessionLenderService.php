<?php

namespace App\Services\Cessions;

use App\Models\Cessions\CessionLender;
use Log;

class CessionLenderService {

    protected $cessionNaturalService;
    protected $cessionLegalService;

    public function __construct(CessionNaturalPersonService $cessionNaturalService, CessionLegalPersonService $cessionLegalService) {
        $this->cessionNaturalService = $cessionNaturalService;
        $this->cessionLegalService = $cessionLegalService;
    }

    public function saveCessionLenderNaturalPerson($lastName, $firstName, $cin, $address, $idGender, $idCession) {

        $idCessionNaturalPerson = $this->cessionNaturalService->saveCessionNaturalPerson(
            $lastName, 
            $firstName, 
            $cin,
            $idGender
        );


        $idCessionNaturalPersonAddress = $this->cessionNaturalService->saveCessionNaturalPersonAddress(
            $address,
            $idCessionNaturalPerson
        );

        $lender = CessionLender::create([
            'id_cession' => $idCession,
            'id_cession_natural_person' => $idCessionNaturalPerson,
            'id_cession_natural_person_address' => $idCessionNaturalPersonAddress,
            'type' => 'natural_person'
        ]);

        return $lender;
    }

    public function saveCessionLenderNaturalPersonExists($idCession, $idCessionNaturalPerson, $idCessionNaturalPersonAddress) {
        $lender = CessionLender::create([
            'id_cession' => $idCession,
            'id_cession_natural_person' => $idCessionNaturalPerson,
            'id_cession_natural_person_address' => $idCessionNaturalPersonAddress,
            'type' => 'natural_person'
        ]);

        return $lender;
    }

    public function saveCessionLenderNaturalPersonExistsNewAddress($idCession, $idCessionNaturalPerson, $address) {
        $idCessionNaturalPersonAddress = $this->cessionNaturalService->saveCessionNaturalPersonAddress(
            $address,
            $idCessionNaturalPerson
        );
        
        $lender = CessionLender::create([
            'id_cession' => $idCession,
            'id_cession_natural_person' => $idCessionNaturalPerson,
            'id_cession_natural_person_address' => $idCessionNaturalPersonAddress,
            'type' => 'natural_person'
        ]);

        return $lender;
    }

    public function saveCessionLenderLegalPerson($name, $address, $idTPI, $idCession) {

        $idCessionLegalPerson = $this->cessionLegalService->saveCessionLegalPerson(
            $name, 
            $address, 
            $idTPI
        );

        $legalPerson = CessionLender::create([
            'id_cession' => $idCession,
            'id_cession_legal_person' => $idCessionLegalPerson,
            'type' => 'legal_person'
        ]);

        return $legalPerson;
    }

    public function saveCessionLenderLegalPersonExists($idCession, $idCessionLegalPerson) {
        $lender = CessionLender::create([
            'id_cession' => $idCession,
            'id_cession_legal_person' => $idCessionLegalPerson,
            'type' => 'legal_person'
        ]);

        return $lender;
    }

    public function updateCesssionLenderNaturalPerson($idCessionLender, $lastName, $firstName, $cin, $idGender, $idCessionNaturalPersonAddress) {

        $lender = CessionLender::findOrFail($idCessionLender);

        $naturalPerson = $this->cessionNaturalService->updateCessionNaturalPerson(
            $lender->id_cession_natural_person, 
            $lastName, 
            $firstName, 
            $cin,
            $idGender
        );
        
        $lender->id_cession_natural_person_address = $idCessionNaturalPersonAddress;
        $lender->save();

        return array_merge(
            $naturalPerson->toArray(),
            $lender->toArray()
        ); 
    }

    public function updateCesssionLenderNaturalPersonNewAddress($idCessionLender, $lastName, $firstName, $cin, $address, $idGender) {

        $lender = CessionLender::findOrFail($idCessionLender);

        $naturalPerson = $this->cessionNaturalService->updateCessionNaturalPerson(
            $lender->id_cession_natural_person, 
            $lastName, 
            $firstName, 
            $cin,
            $idGender
        );

        $idCessionNaturalPersonAddress = $this->cessionNaturalService->saveCessionNaturalPersonAddress(
            $address,
            $lender->id_cession_natural_person
        );
        
        $lender->id_cession_natural_person_address = $idCessionNaturalPersonAddress;
        $lender->save();
        
        return array_merge(
            $naturalPerson->toArray(),
            $lender->toArray()
        ); 
    }

    public function updateCessionLenderLegalPerson($idCessionLender, $name, $address) {
        $lender = CessionLender::findOrFail($idCessionLender);
        
        $legalPerson = $this->$this->cessionLegalService->updateCessionLegalPerson(
            $lender->id_cession_legal_person, 
            $name,
            $address
        );

        return $legalPerson;
    }

    public function deleteCessionLender($idCessionLender) {
        $cessionLender = CessionLender::findOrFail($idCessionLender);

        $cessionLender->delete();
    }

    public function findAllCessionLenderByCession($idCession) {
        $lenders = CessionLender::with(['naturalPerson', 'naturalPersonAddress', 'legalPerson'])
                        ->where('id_cession', $idCession)
                        ->get();

        return $lenders;
    }
}