<?php

namespace App\Services\Cessions;

use App\Models\Cessions\CessionBorrower;

class CessionBorrowerService {
    
    protected $cessionNaturalService;

    public function __construct(CessionNaturalPersonService $cessionNaturalService) {
        $this->cessionNaturalService = $cessionNaturalService;
    }

    public function saveCessionBorrower($lastName, $firstName, $cin, $address, $salaryAmount, $remark, $idGender, $idCession) {

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

        $borrower = CessionBorrower::create([
            'salary_amount' => $salaryAmount,
            'remark' => $remark,
            'id_cession' => $idCession,
            'id_cession_natural_person' => $idCessionNaturalPerson,
            'id_cession_natural_person_address' => $idCessionNaturalPersonAddress,

        ]);

        return $borrower;
    
    }

    public function saveCessionBorrowerExists($salaryAmount, $remark, $idCession, $idCessionNaturalPerson, $idCessionNaturalPersonAddress) {
        $borrower = CessionBorrower::create([
            'salary_amount' => $salaryAmount,
            'remark' => $remark,
            'id_cession' => $idCession,
            'id_cession_natural_person' => $idCessionNaturalPerson,
            'id_cession_natural_person_address' => $idCessionNaturalPersonAddress,
        ]);

        return $borrower;
    }

    public function saveCessionBorrowerExistsNewAddress($salaryAmount, $remark, $idCession, $idCessionNaturalPerson, $address) {
        $idCessionNaturalPersonAddress = $this->cessionNaturalService->saveCessionNaturalPersonAddress(
            $address,
            $idCessionNaturalPerson
        );

        $borrower = CessionBorrower::create([
            'salary_amount' => $salaryAmount,
            'remark' => $remark,
            'id_cession' => $idCession,
            'id_cession_natural_person' => $idCessionNaturalPerson,
            'id_cession_natural_person_address' => $idCessionNaturalPersonAddress,
        ]);

        return $borrower;
    }

    public function updateCesssionBorrower($idCessionBorrower, $lastName, $firstName, $cin, $salaryAmount, $remark, $idGender, $idCessionNaturalPersonAddress) {
        
        $borrower = CessionBorrower::findOrFail($idCessionBorrower);
        
        $naturalPerson = $this->cessionNaturalService->updateCessionNaturalPerson(
            $borrower->id_cession_natural_person, 
            $lastName, 
            $firstName, 
            $cin,
            $idGender
        );
        
        $borrower->salary_amount = $salaryAmount;
        $borrower->remark = $remark;
        $borrower->id_cession_natural_person_address = $idCessionNaturalPersonAddress;
        $borrower->save();


        return array_merge(
            $naturalPerson->toArray(),
            $borrower->toArray()
        );
    }

        
    public function updateCesssionBorrowerNewAddress($idCessionBorrower, $lastName, $firstName, $cin, $address,  $salaryAmount, $remark, $idGender) {
        
        $borrower = CessionBorrower::findOrFail($idCessionBorrower);

        $naturalPerson = $this->cessionNaturalService->updateCessionNaturalPerson(
            $borrower->id_cession_natural_person, 
            $lastName, 
            $firstName, 
            $cin,
            $idGender
        );

        $idCessionNaturalPersonAddress = $this->cessionNaturalService->saveCessionNaturalPersonAddress(
            $address,
            $borrower->id_cession_natural_person
        );

        $borrower->salary_amount = $salaryAmount;
        $borrower->remark = $remark;
        $borrower->id_cession_natural_person_address = $idCessionNaturalPersonAddress;
        $borrower->save();

        return array_merge(
            $naturalPerson->toArray(),
            $borrower->toArray()
        );
    }

    public function deleteCessionBorrower($idCessionBorrower) {
        $borrower = CessionBorrower::findOrFail($idCessionBorrower);

        $borrower->delete();
    }

    public function findAllCessionBorrowerByCession($idCession) {
        $borrowers = CessionBorrower::with(['naturalPerson', 'naturalPersonAddress', 'quota'])
                        ->where('id_cession', $idCession)
                        ->get();

        return $borrowers;
    }

    public function findAllCessionBorrowerHaveQuotaByCession ($idCession) {
        $borrowers = CessionBorrower::with(['naturalPerson.address', 'quota'])
                ->where('id_cession', $idCession)
                ->whereHas('quota')
                ->get();

        return $borrowers;
    }

    public function findCessionBorrower($idCessionBorrower) {
        $borrower = CessionBorrower::findOrFail($idCessionBorrower);
        
        return $borrower;
    }

}

