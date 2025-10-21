<?php

namespace App\Http\Controllers\Cessions;

use App\Exports\CessionExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cessions\CessionInfoRequest;
use App\Http\Requests\Cessions\CessionRequest;
use App\Models\Cessions\Cession;
use App\Models\Cessions\CessionMagistrat;
use App\Services\Cessions\CessionPersonService;
use App\Services\Cessions\CessionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

class CessionController extends Controller {

    protected $cessionService;
    protected $cessionPersonService;
    protected $cessionAssignmentService;

    public function __construct(CessionService $cessionService, CessionPersonService $cessionPersonService) {
        $this->cessionService = $cessionService;
        $this->cessionPersonService = $cessionPersonService;
    }

// ------------------------------- ------------------------------- ------------------------------- Cession
    // public function storeCession (CessionRequest $request) {
    //     try {
    //         $this->authorize('create', Cession::class);

    //         DB::beginTransaction();
            
    //         $data = $request->validated();

            
    //         $idCession = $this->cessionService->saveCession(
    //             $data['numero_dossier'], 
    //             $data['request_subject'], 
    //             $data['reimbursed_amount'], 
    //             $data['date_cession'], 
    //             $data['tpi'], 
    //             $data['user']
    //         );
    
    //         foreach ($data['lenders'] as $value) {
    //             $lender = $this->cessionPersonService->saveCessionLender(
    //                 $value['last_name'], 
    //                 $value['first_name'], 
    //                 $value['address'], 
    //                 $value['cin'],
    //                 $value['gender'],
    //                 $idCession
    //             );

    //         } 
            
    //         foreach ($data['borrowers'] as $value) {

    //             $borrower = $this->cessionPersonService->saveCessionBorrower(
    //                 $value['last_name'], 
    //                 $value['first_name'], 
    //                 $value['address'], 
    //                 $value['cin'],
    //                 $value['salary_amount'], 
    //                 $value['remark'],
    //                 $value['gender'],
    //                 $idCession
    //             );
    //         }        
    
    //         DB::commit();
            
    //         return response()->json([
    //             'cession' => $idCession
    //         ]);

    //     } catch (Exception $e) { 
    //         Log::info($e);
    //         DB::rollBack();
    //     }
    // }

    public function storeCession (CessionRequest $request) {
        $this->authorize('create', Cession::class);
        
        $data = $request->validated();

        $idCession = $this->cessionService->saveCession(
            $data['numero_dossier'], 
            $data['date_contrat'], 
            $data['request_subject'], 
            $data['reimbursed_amount'], 
            $data['date_cession'], 
            $data['tpi'], 
            $data['user']
        );
        
        return response()->json([
            'cession' => $idCession
        ]);
    }
    
    public function editCession ($idCession, CessionInfoRequest $request) {

        $cession = Cession::findOrFail($idCession);

        $this->authorize('update', $cession);

        $data = $request->validated();

        Log::info($data);
        
        $cession = $this->cessionService->updateCession(
            $idCession, 
            $data['numero_dossier'], 
            $data['date_contrat'], 
            $data['request_subject'], 
            $data['reimbursed_amount'], 
            $data['date_cession']
        );
                
        return response()->json([
            'cession' => $cession
        ]);
    }

    public function getCession ($idCession, Request $request) {
        
        $cession = $this->cessionService->findCession($idCession);

        $this->authorize('view', $cession);
        
        return response()->json([
            'cession' => $cession
        ]);
    }

    public function getCessionWithHisMagistrat ($idCession)  {
        $cession = $this->cessionService->findCessionWithHisMagitrat($idCession);

        $this->authorize('view', $cession); 
        
        return response()->json([
            'cession' => $cession
        ]);
    }
    public function getCessionWithAttributCanAccept ($idCession, Request $request) {
        
        $cession = $this->cessionService->findCessionWithAttributCanAccept($idCession);

        $this->authorize('view', $cession);
        
        return response()->json([
            'cession' => $cession
        ]);
    }

    public function getAllCession (Request $request) {

        $this->authorize('viewAny', Cession::class);

        $cessions = $this->cessionService->findAllCession();

        return response()->json([
            'cessions' => $cessions
        ]);
    }

    public function getAllCessionByGreffier ($idUser, Request $request) {


        $this->authorize('viewAny', Cession::class);
        
        $cessions = $this->cessionService->findAllCessionByUser($idUser);

        return response()->json([
            'cessions' => $cessions
        ]);
    }

    public function getAllCessionByTPI ($idTPI) {
        
        $this->authorize('viewAny', Cession::class);
        
        $cessions = $this->cessionService->findAllCessionByTPI($idTPI);

        return response()->json([
            'cessions' => $cessions
        ]);
    }

    public function filterCessionByTPI ($idTPI, Request $request) {
        
        $this->authorize('viewAny', Cession::class);
        
        $statut = $request->input('statut');
        $dateStart = $request->input('dateStart');
        $dateEnd = $request->input('dateEnd');

        $cessions = $this->cessionService->filterCessionByTPI(
            $idTPI, 
            $statut,
            $dateStart,
            $dateEnd,
        );

        return response()->json([
            'cessions' => $cessions
        ]);
    }

    public function acceptCession ($idCession) {

        $cession = CessionMagistrat::where('id_cession', $idCession)->first();

        $this->authorize('action', $cession);

        $this->cessionService->updateCessionStatus($idCession, 2);

        return response()->json([
            'message' => 'Cession acceptée'
        ]);
    }

    public function refuseCession ($idCession) {

        $cession = CessionMagistrat::where('id_cession', $idCession)->first();

        $this->authorize('action', $cession);

        $this->cessionService->updateCessionStatus($idCession, 3);

        return response()->json([
            'message' => 'Cession acceptée'
        ]);
    }

    public function signCession ($idCession) {

        $cession = Cession::findOrFail($idCession);

        $this->authorize('update', $cession);

        $this->cessionService->updateCessionStatus($idCession, 4);

        return response()->json([
            'message' => 'Cession acceptée'
        ]);
    }

// ------------------------------- ------------------------------- ------------------------------- Mention d'execution
    // public function generateMentionExecution ($idCession) {
    //     $cession = $this->cessionService->findCession($idCession);

    //     $this->authorize('update', $cession);

    //     $templatePath = storage_path('app/templates/mention_execution.docx');

    //     if (!file_exists(($templatePath))) {
    //         Log::info('Le fichier modèle n\'existe pas.');
    //         return response()->json(['error' => 'Le fichier modèle n\'existe pas.'], 404);
    //     }

    //     try {

    //         $templateProcessor = new TemplateProcessor($templatePath);   

    //         $templateProcessor->setValues(array(
    //             'numero_dossier' => $cession->numero_dossier,
    //             'numero_ordonnance' => $cession->ordonnance->numero_ordonnance,
    //             'date_cession' => $cession->date_cession,
    //             'request_subject' => $cession->request_subject,
    //             'reimbursed_amount' => $cession->reimbursed_amount
    //         ));

    //         $docxPath = storage_path('app/public/mention_execution_' . time() . '.docx');
    //         $templateProcessor->saveAs($docxPath);

    //         // Charger le DOCX pour conversion
    //         $phpWord = IOFactory::load($docxPath);

    //         // Export en PDF avec Dompdf
    //         $pdfPath = str_replace('.docx', '.pdf', $docxPath);

    //         $domPdfPath = base_path('vendor/dompdf/dompdf');
    //         Settings::setPdfRendererPath($domPdfPath);
    //         Settings::setPdfRendererName('DomPDF');

    //         $pdfWriter = IOFactory::createWriter($phpWord, 'PDF');

    //         $pdfWriter->save($pdfPath);
            
    //         if (file_exists($docxPath)) {
    //             unlink($docxPath);
    //         }

    //         // Retourner le PDF en téléchargement
    //         return response()->download($pdfPath)->deleteFileAfterSend();

    //     } catch (Exception $e) {
    //         return response()->json(['error' => 'Erreur lors de la génération du document : ' . $e->getMessage()], 500);
    //     }
    // }

// ------------------------------- ------------------------------- ------------------------------- Export

    public function exportExcelCessionByTPI($idTPI, Request $request) {

        $statut = $request->input('statut');
        $dateStart = $request->input('dateStart');
        $dateEnd = $request->input('dateEnd');

        $cessions = $this->cessionService->filterCessionByTPI(
            $idTPI, 
            $statut,
            $dateStart,
            $dateEnd,
        );
        
        return Excel::download(new CessionExport($cessions), 'cessions.xlsx');
    }

    public function exportPdfCessionByTPI($idTPI, Request $request) {

        $statut = $request->input('statut');
        $dateStart = $request->input('dateStart');
        $dateEnd = $request->input('dateEnd');
        
        $cessions = $this->cessionService->filterCessionByTPI(
            $idTPI, 
            $statut,
            $dateStart,
            $dateEnd,
        );
                
        $tpi = $cessions[0]->tpi;
        $ca = $tpi->ca;

        $status = [
            0 => 'Toutes',
            1 => 'En cours de traitement',
            2 => 'Acceptée',
            3 => 'Signée',
            4 => 'Clôturée',
        ];

        $pdf = Pdf::loadView('cessions.cession-pdf', [
                'tpi' => $tpi,
                'ca' => $ca,
                'statut' => $status[$statut],
                'dateStart' => $dateStart !== 'null' ? formattedDate($dateStart) : '-',
                'dateEnd' => $dateEnd !== 'null' ? formattedDate($dateEnd) : '-',
                'cessions' => $cessions,
            ])->setPaper('a4', 'landscape'); // Paysage
        
        return $pdf->download('cessions.pdf');

    }


    
// // ------------------------------- ------------------------------- ------------------------------- Cession Draft

//     public function createTempCession(Request $request){
//         try {
//             $data = $request->validate([
//                 'user' => 'required|string',
//                 'key' => 'required|numeric',
//                 'data' => 'required'
//             ]);

//             $file = "cession-".$data['user']."-".$data['key'].".json";

//             $existingData = [];

//             if (Storage::disk('public')->exists('tmp/cessions/'.$file)) {
//                 $json = Storage::disk('public')->get('tmp/cessions/'.$file);
//                 $existingData = json_decode($json, true) ?? [];

//             }

//             foreach ($data['data'] as $key => $value) {
//                 $existingData[$key] = $value;
//             }
            
//             // Sauvegarde dans storage/app/public/fichiers/data.json
//             Storage::disk('public')->put('tmp/cessions/'.$file, json_encode($existingData, JSON_PRETTY_PRINT));

//             return response()->json(['message' => 'Fichier JSON sauvegardé avec succès']);
//         } catch (Exception $e) {
//             Log::info($e);
//         }
//     }

//     public function getTempCession($idUser) {
//         try {
//             $path = storage_path("app/public/tmp/cessions/cession-$idUser-*.json");
//             $files = glob($path);

//             $data = [];

//             foreach ($files as $file) {
//                 $json = file_get_contents($file);
//                 $data[] = [
//                     'file' => basename($file),
//                     'data' => json_decode($json, true)
//                 ]; // Décoder en tableau associatif
//             }

//             return response()->json([
//                 'temp_cessions' => $data
//             ]);
//         } catch (Exception $e) {
            
//         }
//     }

//     public function deleteTempCession($idUser, Request $request) {
//         try {
//             $data = $request->validate([
//                 'file_name' => 'required|string'
//             ]);


//             if (Storage::disk('public')->exists('tmp/cessions/'.$data['file_name'])) {
//                 Log::info($data['file_name']);
//                 Storage::disk('public')->delete('tmp/cessions/'.$data['file_name']);
//             }

//             return response()->json([
//                 'message' => "Fichier temporaire supprimé"
//             ]);
//         } catch (Exception $e) {
            
//         }
//     }
}  
