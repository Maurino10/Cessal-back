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
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CessionController extends Controller {
    protected $cessionService;
    protected $cessionPersonService;
    protected $cessionAssignmentService;

    public function __construct(CessionService $cessionService, CessionPersonService $cessionPersonService) {
        $this->cessionService = $cessionService;
        $this->cessionPersonService = $cessionPersonService;
    }

// ------------------------------- ------------------------------- ------------------------------- Cession
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

        $idTpi = $request->input('tpi');
        $statut = $request->input('statut');
        $dateStart = $request->input('dateStart');
        $dateEnd = $request->input('dateEnd');

        $cessions = $this->cessionService->findAllCession($idTpi, $statut, $dateStart, $dateEnd);

        return response()->json([
            'cessions' => $cessions
        ]);
    }

    public function getAllCessionByGreffier ($idUser, Request $request) {

        $this->authorize('viewAny', Cession::class);

        $search = $request->input('search');
        $statut = $request->input('statut');
        
        $cessions = $this->cessionService->findAllCessionByGreffier($idUser, $search, $statut);

        return response()->json([
            'cessions' => $cessions
        ]);
    }

    public function getAllCessionByTPI ($idTPI, Request $request) {
        
        $this->authorize('viewAny', Cession::class);

        $statut = $request->input('statut');
        $dateStart = $request->input('dateStart');
        $dateEnd = $request->input('dateEnd');
        
        $cessions = $this->cessionService->findAllCessionByTPI($idTPI, $statut, $dateStart, $dateEnd);

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

    public function cessionIsSigned ($idCession) {
        $cession = Cession::findOrFail($idCession);

        $this->authorize('update', $cession);

        $this->cessionService->cessionIsSigned($idCession, 1);

        return response()->json([
            'message' => 'Cession signée'
        ]);
    }

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
                

        $status = [
            -1 => 'Toutes',
            0 => 'Enregistrée',
            1 => 'En cours de traitement',
            2 => 'Acceptée',
            3 => 'Refusée',
            4 => 'Signée',
        ];

        $pdf = Pdf::loadView('cessions.cession-pdf', [
                'statut' => $status[$statut],
                'dateStart' => $dateStart !== 'null' ? formattedDate($dateStart, 'D/MM/YYYY') : '-',
                'dateEnd' => $dateEnd !== 'null' ? formattedDate($dateEnd, 'D/MM/YYYY') : '-',
                'cessions' => $cessions,
            ])
            ->setPaper('a4', 'landscape') // Paysage
            ->setWarnings(false)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('enable_php', true);

        return $pdf->download('cessions.pdf');

    }

    public function exportExcelCession(Request $request) {

        $idTpi = $request->input('tpi');
        $statut = $request->input('statut');
        $dateStart = $request->input('dateStart');
        $dateEnd = $request->input('dateEnd');

        $cessions = $this->cessionService->filterCession(
            $idTpi, 
            $statut,
            $dateStart,
            $dateEnd,
        );  

        return Excel::download(new CessionExport($cessions), 'cessions.xlsx');
    }

    public function exportPdfCession(Request $request) {

        $idTpi = $request->input('tpi');
        $statut = $request->input('statut');
        $dateStart = $request->input('dateStart');
        $dateEnd = $request->input('dateEnd');
        
        $cessions = $this->cessionService->filterCession(
            $idTpi, 
            $statut,
            $dateStart,
            $dateEnd,
        );

        $cessions = $cessions->sort(function ($a, $b) {
            if ($a->id_tpi === $b->id_tpi) {
                return $b->date_cession <=> $a->date_cession;
            }
            return $a->id_tpi <=> $b->id_tpi;
        })->values();
                


        $status = [
            -1 => 'Toutes',
            0 => 'Enregistrée',
            1 => 'En cours de traitement',
            2 => 'Acceptée',
            3 => 'Refusée',
            4 => 'Signée',
        ];

        $pdf = Pdf::loadView('cessions.cession-pdf', [
                'statut' => $status[$statut],
                'dateStart' => $dateStart !== 'null' ? formattedDate($dateStart, 'D/MM/YYYY') : '-',
                'dateEnd' => $dateEnd !== 'null' ? formattedDate($dateEnd, 'D/MM/YYYY') : '-',
                'cessions' => $cessions,
            ])
            ->setPaper('a4', 'landscape')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('enable_php', true)
            ->setOption('dpi', 100)
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setWarnings(false);
            
        return $pdf->download('cessions.pdf');

    }
}  
