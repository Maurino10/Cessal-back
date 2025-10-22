<?php

namespace App\Http\Controllers\Cessions;

use App\Http\Controllers\Controller;
use App\Services\Cessions\CessionPersonService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PersonController extends Controller {

    protected $cessionPersonService;

    public function __construct(CessionPersonService $cessionPersonService) {
        $this->cessionPersonService = $cessionPersonService;
    }

    public function checkCIN ($cin) {
        $naturalPerson = $this->cessionPersonService->findCINInCessionNaturalPerson($cin);

        if ($naturalPerson) {
            return response()->json([
                'exists' => true,
                'natural_person' => $naturalPerson
            ]);
        }

        return response()->json(['exists' => false]);
    }

    public function getEntityByTPI ($idTPI) {
        $legalPersons = $this->cessionPersonService->findLegalPersonByTPI($idTPI);

        return response()->json([
            'legal_persons' => $legalPersons
        ]);
    }

    public function getAllAddressCessionNaturalPerson($idCessionNaturalPerson) {
        $addresses = $this->cessionPersonService->findAllAddressCessionNaturalPerson($idCessionNaturalPerson);

        return response()->json([
            'addresses' => $addresses
        ]);
    }
}