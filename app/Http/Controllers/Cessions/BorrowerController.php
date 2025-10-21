<?php

namespace App\Http\Controllers\Cessions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cessions\CessionBorrowerRequest;
use App\Http\Requests\Cessions\CessionReferenceRequest;
use App\Models\Cessions\Cession;
use App\Services\Cessions\CessionPersonService;
use App\Services\Cessions\CessionProvisionService;
use App\Services\Cessions\CessionReferenceService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpWord\TemplateProcessor;

class BorrowerController extends Controller {


    protected $cessionPersonService;
    protected $cessionProvisionService;
    protected $cessionReferenceService;

    public function __construct(CessionPersonService $cessionPersonService, CessionReferenceService $cessionReferenceService, CessionProvisionService $cessionProvisionService) {
        $this->cessionPersonService = $cessionPersonService;
        $this->cessionProvisionService = $cessionProvisionService;
        $this->cessionReferenceService = $cessionReferenceService;
    }


    public function storeCessionBorrower ($idCession, CessionBorrowerRequest $request) {
        
            $cession = Cession::findOrFail($idCession);

            $this->authorize('store', $cession);

            $data = $request->validated();
    
            $borrower = $this->cessionPersonService->saveCessionBorrower(
                $data['last_name'], 
                $data['first_name'], 
                $data['cin'],
                $data['salary_amount'], 
                $data['remark'],
                $data['gender'],
                $idCession
            );

            return response()->json([
                'borrower' => $borrower
            ]);
    }

    public function storeCessionBorrowerExists ($idCession, Request $request) {
        try {
            $cession = Cession::findOrFail($idCession);
    
            $this->authorize('store', $cession);
            
            $data = $request->validate([
                'party' => 'required|numeric',
                'salary_amount' => 'required|numeric|min:0',
                'remark' => 'nullable|string',
            ], [
                'salary_amount.required' => 'Le revenu est obligatoire.',
                'salary_amount.numeric' => 'Le revenu doit être un nombre.',
                'salary_amount.min' => 'Le revenu doit être positif.',
            ]);
            
            $borrower = $this->cessionPersonService->saveCessionBorrowerExists(
                $data['salary_amount'],
                $data['remark'],
                $idCession,
                $data['party']
            );
    
    
            return response()->json([
                'borrower' => $borrower
            ]);
        } catch (ValidationException $ve) {
            return response()->json([
                'errors' => $ve->errors()
            ], 422);
        }
    }

       public function storeCessionBorrowerExistsNewAddress ($idCession, Request $request) {
        try {
            $cession = Cession::findOrFail($idCession);
    
            $this->authorize('store', $cession);
            
            $data = $request->validate([
                'party' => 'required|numeric',
                'address' => 'required|string',
                'salary_amount' => 'required|numeric|min:0',
                'remark' => 'nullable|string',
            ], [
                'address.required' => 'L’adresse est obligatoire.',
                'address.string' => 'L’adresse doit être une chaîne de caractères.',
                'salary_amount.required' => 'Le revenu est obligatoire.',
                'salary_amount.numeric' => 'Le revenu doit être un nombre.',
                'salary_amount.min' => 'Le revenu doit être positif.',
            ]);
            
            $borrower = $this->cessionPersonService->saveCessionBorrowerExists(
                $data['salary_amount'],
                $data['remark'],
                $idCession,
                $data['party']
            );
    
            $address = $this->cessionPersonService->saveCessionPartyAddress(
                $data['address'],
                $data['party']
            );

            return response()->json([
                'borrower' => $borrower
            ]);
        } catch (ValidationException $ve) {
            return response()->json([
                'errors' => $ve->errors()
            ], 422);
        }
    }
    public function editCessionBorrower($idCession, $idCessionBorrower, CessionBorrowerRequest $request) {

        $cession = Cession::findOrFail($idCession);

        $this->authorize('store', $cession);

        $data = $request->validated();

        $borrower = $this->cessionPersonService->updateCesssionBorrower(
            $idCessionBorrower,
            $data['last_name'], 
            $data['first_name'], 
            $data['address'], 
            $data['cin'], 
            $data['salary_amount'], 
            $data['remark'],
            $data['gender']
        );


        return response()->json([
            'borrower' => $borrower
        ]);
    }

    public function removeCessionBorrower($idCession, $idCessionBorrower) {

        $cession = Cession::findOrFail($idCession);

        $this->authorize('store', $cession);
        
        $this->cessionPersonService->deleteCessionBorrower($idCessionBorrower);

        return response()->json([
            'message' => 'Défendeur supprimé avec succés'
        ]);
    }

    public function getAllCessionBorrowerByCession($idCession) {
        $cession = Cession::findOrFail($idCession);


        $this->authorize('view', $cession);

        $borrowers = $this->cessionPersonService->findAllCessionBorrowerByCession($idCession);

        return response()->json([
            'borrowers' => $borrowers
        ]);
    }

    public function getAllCessionBorrowerHaveQuotaByCession($idCession) {
        $cession = Cession::findOrFail($idCession);


        $this->authorize('view', $cession);

        $borrowers = $this->cessionPersonService->findAllCessionBorrowerHaveQuotaByCession($idCession);

        return response()->json([
            'borrowers' => $borrowers
        ]);
    }

    public function getCessionBorrower($idCession, $idCessionBorrower) {
        $cession = Cession::findOrFail($idCession);

        $this->authorize('view', $cession);

        $borrower = $this->cessionPersonService->findCessionBorrower($idCessionBorrower);
        $borrower->load(['party', 'quota', 'reference']);

        return response()->json([
            'borrower' => $borrower
        ]);
    }


    public function generateDeclaration($idCession, $idCessionBorrower, $idCessionReference)
    {
        
        try {

            $cession = Cession::find($idCession);
            $this->authorize('update', $cession);

            $reference = $this->cessionReferenceService->findReference($idCessionReference);
            
            $magistrat = $cession->assignment->user->profil;
            $borrower = $this->cessionPersonService->findCessionBorrower($idCessionBorrower);

            $lenders = $cession->lenders;

            $entities = [];
            $persons = [];

            foreach ($lenders as $lender) {
                if ($lender->type === 'person') {
                    $persons[] =  trim($lender->party->last_name . ' ' . $lender->party->first_name);
                } else {
                    $entities[] = trim($lender->entity->name);
                }
            }

            $lendersList = array_merge($entities, $persons);
            $lendersString = implode(', ', $lendersList);

            Log::info(count($entities));

            // Chemin vers le modèle Word
            $templatePath = storage_path('app/templates/Declaration_de_cession_volontaire_sur_salaire.docx');
    
            if (!file_exists($templatePath)) {
                Log::error("Le fichier modèle n'existe pas : {$templatePath}");
                return response()->json(['error' => 'Le fichier modèle n\'existe pas.'], 404);
            }
            // 🧩 Étape 1 : Charger le modèle Word et injecter les variables
            $templateProcessor = new TemplateProcessor($templatePath);
            
            $templateProcessor->setValues([
                'TPI' => str_replace('TPI', '', $cession->tpi->name),
                'provision' => number_format($reference->provision, 0, ',', ' '),
                'recu' => $reference->numero_recu,
                'feuillet' => $reference->numero_feuillet,
                'repertoire' => $reference->numero_repertoire,
                'date' => formattedDate($reference->date),
                'magistrat' => appelation($magistrat),
                'borrower' => appelation($borrower->party),
                'address' => $borrower->party->address,
                'lenders' => count($entities) === 0 ? 'de '. $lendersString : 'de la '. $lendersString,
                'granted_amount_number' => number_format($borrower->quota->granted_amount, 0, ',', ' '),
                'granted_amount_letter' => spell_money_ariary($borrower->quota->granted_amount),
                'reimbursed_amount_number' => number_format($cession->reimbursed_amount, 0, ',', ' '),
                'reimbursed_amount_letter' => spell_money_ariary($cession->reimbursed_amount),
            ]);

            // 🧾 Étape 2 : Sauvegarder le DOCX temporaire
            $docxPath = storage_path('app/public/declaration_' . time() . '.docx');
            $templateProcessor->saveAs($docxPath);

            // Vérifie que le fichier a bien été créé
            if (!file_exists($docxPath)) {
                return response()->json(['error' => 'Erreur : le fichier DOCX n’a pas été généré.'], 500);
            }

            // 🧱 Étape 3 : Conversion DOCX -> PDF via LibreOffice
            $pdfPath = str_replace('.docx', '.pdf', $docxPath);

            // Commande sécurisée avec escapeshellarg
            $command = sprintf(
                'soffice --headless --convert-to pdf --outdir %s %s',
                escapeshellarg(dirname($docxPath)),
                escapeshellarg($docxPath)
            );

            exec($command, $output, $returnCode);

            if ($returnCode !== 0 || !file_exists($pdfPath)) {
                Log::error("Erreur LibreOffice : " . implode("\n", $output));
                return response()->json(['error' => 'Erreur lors de la conversion PDF.'], 500);
            }

            // 🧹 Étape 4 : Nettoyage — supprimer le DOCX temporaire
            if (file_exists($docxPath)) {
                unlink($docxPath);
            }

            // 🧾 Étape 5 : Télécharger le PDF et supprimer après envoi
            return response()->download($pdfPath)->deleteFileAfterSend();

        } catch (\Exception $e) {
            Log::error("Erreur lors de la génération du document : " . $e->getMessage());
            return response()->json([
                'error' => 'Erreur lors de la génération du document : ' . $e->getMessage(),
            ], 500);
        }
    }

}   