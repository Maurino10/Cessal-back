<?php

namespace App\Http\Controllers\Cessions;

use App\Http\Controllers\Controller;
use App\Models\Cessions\Cession;
use App\Services\Cessions\CessionJustificatifService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class JustificatifController extends Controller
{


    protected $cessionJustificatifService;
    public function __construct(CessionJustificatifService $cessionJustificatifService)
    {

        $this->cessionJustificatifService = $cessionJustificatifService;
    }

    public function storeCessionJustificatifs($idCession, Request $request)
    {
        try {
            $cession = Cession::findOrFail($idCession);
            
            $this->authorize('store', $cession);

            $request->validate([
                'files.*' => 'required|mimes:pdf|max:5120',
            ]);

            $justifs = [];

            foreach ($request->file('files') as $file) {
                $path = $file->store('cessions/justificatifs', 'public');

                $justifs[] = $this->cessionJustificatifService->saveCessionJustificatif(
                    $file->getClientOriginalName(),
                    $path,
                    $file->getMimeType(),
                    $file->getSize(),
                    $idCession
                );
            }

            return response()->json([
                'message' => 'Fichiers uploadés avec succès',
                'justificatifs' => $justifs
            ]);
        } catch (ValidationException $e) {
            Log::info($e);
        }
    }

    public function showCessionJustificatif($idCession, $idCessionJustificatif)
    {
        try {

            $cession = Cession::findOrFail($idCession);

            $this->authorize('view', $cession);



            $justif = $this->cessionJustificatifService->findCessionJustificatif($idCession, $idCessionJustificatif);

            if (!Storage::disk('public')->exists($justif->path)) {
                abort(404);
            }


            $fileContent = Storage::disk('public')->get($justif->path);

            return response($fileContent, 200)
                ->header('Content-Type', $justif->type)
                ->header('Content-Disposition', 'inline; filename="' . $justif->name . '"');
        } catch (Exception $ve) {
            Log::info($ve);
        }

    }

    public function removeCessionJustificatif($idCession, $idCessionJustificatif)
    {
        try {

            $cession = Cession::findOrFail($idCession);

            $this->authorize('delete', $cession);


            $this->cessionJustificatifService->deleteCessionJustificatif($idCession, $idCessionJustificatif);


            return response([
                'message' => 'Pièce suppriées avec succés'
            ]);

        } catch (Exception $ve) {
            Log::info($ve);
        }

    }

    public function getAllCessionJustificatifByCession($idCession) {
        $cession = Cession::findOrFail($idCession);
        
        $this->authorize('view', $cession);

        $justifs = $this->cessionJustificatifService->findAllCessionJustificatifByCession($idCession);

        return response()->json([
            'justifs' => $justifs
        ]);
    }

}