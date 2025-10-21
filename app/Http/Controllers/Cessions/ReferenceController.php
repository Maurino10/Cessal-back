<?php

namespace App\Http\Controllers\Cessions;

use App\Http\Controllers\Controller;

use App\Http\Requests\Cessions\CessionReferenceRequest;
use App\Models\Cessions\Cession;
use App\Services\Cessions\CessionReferenceService;

class ReferenceController extends Controller {
    protected $cessionReferenceService;

    public function __construct(CessionReferenceService $cessionReferenceService) {
        $this->cessionReferenceService = $cessionReferenceService;
    }

    public function storeCessionReference ($idCession, $idCessionBorrower, CessionReferenceRequest $request) {
        
        $cession = Cession::find($idCession);
        $this->authorize('update', $cession);

        $data = $request->validated();
        
        $reference = $this->cessionReferenceService->saveReference(
            $data['numero_recu'],
            $data['numero_feuillet'],
            $data['numero_repertoire'],
            $data['date'],
            $idCessionBorrower
        );

        return response()->json([
            'reference' => $reference
        ]);
    }

    public function editCessionReference ($idCession, $idCessionBorrower, $idCessionReference, CessionReferenceRequest $request) {
        
        $cession = Cession::find($idCession);
        $this->authorize('update', $cession);

        $data = $request->validated();
        
        $reference = $this->cessionReferenceService->updateReference(
            $idCessionReference,
            $data['numero_recu'],
            $data['numero_feuillet'],
            $data['numero_repertoire'],
            $data['date'],
        );

        return response()->json([
            'reference' => $reference
        ]);
    }
}