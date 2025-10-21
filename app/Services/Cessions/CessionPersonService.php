<?php

namespace App\Services\Cessions;

use App\Models\Cessions\CessionBorrower;
use App\Models\Cessions\CessionEntity;
use App\Models\Cessions\CessionParty;
use App\Models\Cessions\CessionLender;
use App\Models\Cessions\CessionPartyAddress;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CessionPersonService {

// ------------------------------------------------------------------------------------------------------------- Cession Party
    public function saveCessionParty($lastName, $firstName, $cin, $idGender) {

        $party = CessionParty::insertGetId([
            'last_name' => $lastName,
            'first_name' => $firstName,
            'cin' => $cin,
            'id_gender' => $idGender
        ]);
        
        return $party;
    }

    public function saveCessionPartyAddress($address, $idCessionParty) {

        $address = CessionPartyAddress::create([
            'address' => $address,
            'date_address' => Carbon::now(),
            'id_cession_party' => $idCessionParty
        ]);

        return $address;
    }

    public function saveCessionEntity($name, $address, $idTPI) {
        $entity = CessionEntity::insertGetId([
            'name' => $name,
            'address' => $address,
            'id_tpi' => $idTPI
        ]);

        return $entity;
    }

    public function updateCessionParty($idCessionParty, $lastName, $firstName, $address, $cin, $idGender) {

        $party = CessionParty::findOrFail($idCessionParty);

        $party->last_name = $lastName;
        $party->first_name = $firstName;
        $party->cin = $cin;
        $party->id_gender = $idGender;
        $party->save();
        
        return $party;
    }

    public function updateCessionEntity($idCessionEntity, $name, $address) {

        $entity = CessionEntity::findOrFail($idCessionEntity);

        $entity->name = $name;
        $entity->address = $address;
        $entity->save();
        
        return $entity;
    }

    public function deleteCessionPerson($idCessionParty) {
        $party = CessionParty::findOrFail($idCessionParty);
        $party->delete();
    }

// ------------------------------------------------------------------------------------------------------------- Lender
    public function saveCessionLender($lastName, $firstName, $cin, $idGender, $idCession) {

        $idCessionParty = $this->saveCessionParty(
            $lastName, 
            $firstName, 
            $cin,
            $idGender
        );


        $lender = CessionLender::create([
            'id_cession' => $idCession,
            'id_cession_party' => $idCessionParty,
            'type' => 'person'
        ]);

        return $lender;
    }

    public function saveCessionLenderExists($idCession, $idCessionParty) {
        $lender = CessionLender::create([
            'id_cession' => $idCession,
            'id_cession_party' => $idCessionParty,
            'type' => 'person'
        ]);

        return $lender;
    }

    public function saveCessionLenderEntity($name, $address, $idTPI, $idCession) {

        $idCessionEntity = $this->saveCessionEntity(
            $name, 
            $address, 
            $idTPI
        );

        $entity = CessionLender::create([
            'id_cession' => $idCession,
            'id_cession_entity' => $idCessionEntity,
            'type' => 'entity'
        ]);

        return $entity;
    }

    public function saveCessionLenderEntityExists($idCession, $idCessionEntity) {
        $lender = CessionLender::create([
            'id_cession' => $idCession,
            'id_cession_entity' => $idCessionEntity,
            'type' => 'entity'
        ]);

        return $lender;
    }

    public function updateCesssionLender($idCessionLender, $lastName, $firstName, $address, $cin, $idGender) {

        $lender = CessionLender::findOrFail($idCessionLender);

        $party = $this->updateCessionParty(
            $lender->id_cession_party, 
            $lastName, 
            $firstName, 
            $address, 
            $cin,
            $idGender
        );

        return $party;
    }

    public function updateCessionLenderEntity($idCessionLender, $name, $address) {
        $lender = CessionLender::findOrFail($idCessionLender);
        
        $entity = $this->updateCessionEntity(
            $lender->id_cession_entity, 
            $name,
            $address
        );

        return $entity;
    }

    public function deleteCessionLender($idCessionLender) {
        $cessionLender = CessionLender::findOrFail($idCessionLender);

        $cessionLender->delete();
    }
    public function findAllCessionLenderByCession($idCession) {
        $lenders = CessionLender::with(['party', 'entity'])
                        ->where('id_cession', $idCession)
                        ->get();

        return $lenders;
    }
// ------------------------------------------------------------------------------------------------------------- Borrower
    public function saveCessionBorrower($lastName, $firstName, $cin, $salaryAmount, $remark, $idGender, $idCession) {

        $idCessionParty = $this->saveCessionParty(
            $lastName, 
            $firstName, 
            $cin,
            $idGender
        );

        $borrower = CessionBorrower::create([
            'salary_amount' => $salaryAmount,
            'remark' => $remark,
            'id_cession' => $idCession,
            'id_cession_party' => $idCessionParty
        ]);

        return $borrower;
    
    }

    public function saveCessionBorrowerExists($salaryAmount, $remark, $idCession, $idCessionParty) {
        $borrower = CessionBorrower::create([
            'salary_amount' => $salaryAmount,
            'remark' => $remark,
            'id_cession' => $idCession,
            'id_cession_party' => $idCessionParty,
        ]);

        return $borrower;
    }

    public function updateCesssionBorrower($idCessionBorrower, $lastName, $firstName, $address, $cin, $salaryAmount, $remark, $idGender) {
        
        $borrower = CessionBorrower::findOrFail($idCessionBorrower);
        $borrower->salary_amount = $salaryAmount;
        $borrower->remark = $remark;
        $borrower->save();

        $party = $this->updateCessionParty(
            $borrower->id_cession_party, 
            $lastName, 
            $firstName, 
            $address,
             $cin,
             $idGender
        );

        return array_merge(
            $party->toArray(),
            $borrower->toArray()
        );
    }

    public function deleteCessionBorrower($idCessionBorrower) {
        $borrower = CessionBorrower::findOrFail($idCessionBorrower);

        $borrower->delete();
    }

    public function findAllCessionBorrowerByCession($idCession) {
        $borrowers = CessionBorrower::with(['party', 'quota'])
                        ->where('id_cession', $idCession)
                        ->get();

        return $borrowers;
    }

    public function findAllCessionBorrowerHaveQuotaByCession ($idCession) {
        $borrowers = CessionBorrower::with(['party', 'quota'])
                ->where('id_cession', $idCession)
                ->whereHas('quota')
                ->get();

        return $borrowers;
    }

    public function findCessionBorrower($idCessionBorrower) {
        $borrower = CessionBorrower::findOrFail($idCessionBorrower);
        
        return $borrower;
    }
// ------------------------------------------------------------------------------------------------------------- Person

    public function findCINInCessionParty ($cin) {
        $party = CessionParty::with(['address'])->where('cin', $cin)->first();
        
        return $party;
    }

    public function findEntityByTPI ($idTPI) {
        $entities = CessionEntity::where('id_tpi', $idTPI)->get();

        return $entities;
    }
}

