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
        $party = $this->cessionPersonService->findCINInCessionParty($cin);

        if ($party) {
            return response()->json([
                'exists' => true,
                'party' => $party
            ]);
        }

        return response()->json(['exists' => false]);
    }

    public function getEntityByTPI ($idTPI) {
        $entities = $this->cessionPersonService->findEntityByTPI($idTPI);

        return response()->json([
            'entities' => $entities
        ]);
    }
}