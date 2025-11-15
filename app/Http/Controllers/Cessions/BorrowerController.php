<?php

namespace App\Http\Controllers\Cessions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cessions\CessionBorrowerRequest;
use App\Models\Cessions\Cession;
use App\Services\Cessions\CessionBorrowerService;
use App\Services\Cessions\CessionNaturalPersonService;
use App\Services\Cessions\CessionProvisionService;
use App\Services\Cessions\CessionReferenceService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpWord\TemplateProcessor;

class BorrowerController extends Controller {


    protected $cessionBorrowerService;
    protected $cessionNaturalPersonService;
    protected $cessionProvisionService;
    protected $cessionReferenceService;

    public function __construct(CessionBorrowerService $cessionBorrowerService, CessionNaturalPersonService $cessionNaturalPersonService, CessionReferenceService $cessionReferenceService, CessionProvisionService $cessionProvisionService) {
        $this->cessionBorrowerService = $cessionBorrowerService;
        $this->cessionNaturalPersonService = $cessionNaturalPersonService;
        $this->cessionProvisionService = $cessionProvisionService;
        $this->cessionReferenceService = $cessionReferenceService;
    }


    public function storeCessionBorrower ($idCession, CessionBorrowerRequest $request) {
        
            $cession = Cession::findOrFail($idCession);

            $this->authorize('store', $cession);

            $data = $request->validated();
    
            $borrower = $this->cessionBorrowerService->saveCessionBorrower(
                $data['last_name'], 
                $data['first_name'], 
                $data['cin'],
                $data['address'],
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
                'natural_person' => 'required|numeric',
                'natural_person_address' => 'required|numeric',
                'salary_amount' => 'required|numeric|min:0',
                'remark' => 'nullable|string',
            ], [
                'salary_amount.required' => 'Le revenu est obligatoire.',
                'salary_amount.numeric' => 'Le revenu doit être un nombre.',
                'salary_amount.min' => 'Le revenu doit être positif.',
            ]);
            
            $borrower = $this->cessionBorrowerService->saveCessionBorrowerExists(
                $data['salary_amount'],
                $data['remark'],
                $idCession,
                $data['natural_person'],
                $data['natural_person_address'],
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
                'natural_person' => 'required|numeric',
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
            
            $borrower = $this->cessionBorrowerService->saveCessionBorrowerExistsNewAddress(
                $data['salary_amount'],
                $data['remark'],
                $idCession,
                $data['natural_person'],
                $data['address'],
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

        $borrower = $this->cessionBorrowerService->updateCesssionBorrower(
            $idCessionBorrower,
            $data['last_name'], 
            $data['first_name'], 
            $data['cin'], 
            $data['salary_amount'], 
            $data['remark'], 
            $data['gender'],
            $data['address']
        );


        return response()->json([
            'borrower' => $borrower
        ]);
    }

    public function editCessionBorrowerNewAddress($idCession, $idCessionBorrower, CessionBorrowerRequest $request) {

        $cession = Cession::findOrFail($idCession);

        $this->authorize('store', $cession);

        $data = $request->validated();

        $borrower = $this->cessionBorrowerService->updateCesssionBorrowerNewAddress(
            $idCessionBorrower,
            $data['last_name'], 
            $data['first_name'], 
            $data['cin'], 
            $data['address'],
            $data['salary_amount'], 
            $data['remark'], 
            $data['gender'],
        );


        return response()->json([
            'borrower' => $borrower
        ]);
    }

    public function removeCessionBorrower($idCession, $idCessionBorrower) {

        $cession = Cession::findOrFail($idCession);

        $this->authorize('store', $cession);
        
        $this->cessionBorrowerService->deleteCessionBorrower($idCessionBorrower);

        return response()->json([
            'message' => 'Défendeur supprimé avec succés'
        ]);
    }

    public function getAllCessionBorrowerByCession($idCession) {
        $cession = Cession::findOrFail($idCession);


        $this->authorize('view', $cession);

        $borrowers = $this->cessionBorrowerService->findAllCessionBorrowerByCession($idCession);

        return response()->json([
            'borrowers' => $borrowers
        ]);
    }

    public function getAllCessionBorrowerHaveQuotaByCession($idCession) {
        $cession = Cession::findOrFail($idCession);


        $this->authorize('view', $cession);

        $borrowers = $this->cessionBorrowerService->findAllCessionBorrowerHaveQuotaByCession($idCession);

        return response()->json([
            'borrowers' => $borrowers
        ]);
    }

    public function getCessionBorrower($idCession, $idCessionBorrower) {
        $cession = Cession::findOrFail($idCession);

        $this->authorize('view', $cession);

        $borrower = $this->cessionBorrowerService->findCessionBorrower($idCessionBorrower);
        $borrower->load(['naturalPerson', 'naturalPersonAddress', 'quota', 'reference']);

        return response()->json([
            'borrower' => $borrower
        ]);
    }

    public function generateDeclaration($idCession, $idCessionBorrower, $idCessionReference)
    {
        
        try {

            $cession = Cession::find($idCession);
            $this->authorize('update', $cession);

            $provision = $this->cessionProvisionService->findProvisionDateCession($cession->date_cession);
            $reference = $this->cessionReferenceService->findReference($idCessionReference);
            
            $magistrat = $cession->assignment->user->profil;
            $borrower = $this->cessionBorrowerService->findCessionBorrower($idCessionBorrower);

            $lenders = $cession->lenders;

            $legalPerson = [];
            $naturalPerson = [];

            foreach ($lenders as $lender) {
                if ($lender->type === 'natural_person') {
                    $naturalPerson[] =  trim($lender->naturalPerson->last_name . ' ' . $lender->naturalPerson->first_name);
                } else {
                    $legalPerson[] = trim($lender->legalPerson->name . ' ' . $lender->legalPersonAddress->address);
                }
            }

            $lendersList = array_merge($legalPerson, $naturalPerson);
            $lendersString = implode(', ', $lendersList);

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
                'provision' => number_format($provision->provision_amount, 0, ',', ' '),
                'recu' => $reference->numero_recu,
                'feuillet' => $reference->numero_feuillet,
                'repertoire' => $reference->numero_repertoire,
                'date' => formattedDate($reference->date, 'D MMM YYYY'),
                'magistrat' => appelation($magistrat),
                'borrower' => appelation($borrower->naturalPerson),
                'address' => $borrower->naturalPersonAddress->address,
                'lenders' => count($legalPerson) === 0 ? 'de '. $lendersString : 'de la '. $lendersString,
                'date_contrat' => formattedDate($cession->date_contrat, 'D/MM/YYYY'),
                'granted_amount_number' => number_format($borrower->quota->granted_amount, 0, ',', ' '),
                'granted_amount_letter' => spell_money_ariary($borrower->quota->granted_amount),
                'reimbursed_amount_number' => number_format($cession->reimbursed_amount, 0, ',', ' '),
                'reimbursed_amount_letter' => spell_money_ariary($cession->reimbursed_amount),
                'do_date' => Carbon::now()->format('d/m/Y')
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