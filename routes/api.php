<?php

use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Cessions\MagistratController;
use App\Http\Controllers\Cessions\CessionController;
use App\Http\Controllers\Cessions\BorrowerController;
use App\Http\Controllers\Cessions\BorrowerQuotaController;
use App\Http\Controllers\Cessions\JustificatifController;
use App\Http\Controllers\Cessions\OrdonnanceController;
use App\Http\Controllers\Cessions\LenderController;
use App\Http\Controllers\Cessions\PersonController;
use App\Http\Controllers\Cessions\ProvisionController;
use App\Http\Controllers\Cessions\ReferenceController;
use App\Http\Controllers\Instances\CaController;
use App\Http\Controllers\Instances\TpiController;
use App\Http\Controllers\Territories\DistrictController;
use App\Http\Controllers\Territories\ProvinceController;
use App\Http\Controllers\Territories\RegionController;
use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/register', [AdminController::class, 'register']);

Route::get('/public/tpi', [TpiController::class, 'getAllWithoutRelations']);
Route::get('/public/posts', [UserController::class, 'getAllPost']);
Route::get('/public/genders', [UserController::class, 'getAllGender']);

// Routes protégées admin
Route::middleware('auth:admin')->group(function () {


    Route::controller(ProvinceController::class)->group(function () {
        Route::post('/provinces','storeProvince');
        Route::put('/provinces/{idProvince}','editProvince');
        Route::delete('/provinces/{idProvince}','removeProvince');
        Route::get('/provinces/{idProvince}','getProvince');
        Route::get('/provinces','getAllProvince');
    });

    Route::controller(CaController::class)->group(function () {
        Route::post('/ca','storeCA');
        Route::put('/ca/{idCA}','editCA');
        Route::delete('/ca/{idCA}','removeCA');
        Route::get('/ca/{idCA}','getCA');
        Route::get('/ca','getAllCA');
        Route::get('/ca-filter','filterCA');
    });

    Route::controller(RegionController::class)->group(function () {
        Route::post('/regions','storeRegion');
        Route::put('/regions/{idRegion}','editRegion');
        Route::delete('/regions/{idRegion}','removeRegion');
        Route::get('/regions/{idRegion}','getRegion');
        Route::get('/regions','getAllRegion');
        Route::get('/regions-filter','filterRegion');
    });

    Route::controller(DistrictController::class)->group(function () {
        Route::post('/districts','storeDistrict');
        Route::put('/districts/{idDistrict}','editDistrict');
        Route::delete('/districts/{idDistrict}','removeDistrict');
        Route::get('/districts/{idDistrict}','getDistrict');
        Route::get('/districts','getAllDistrict');
        Route::get('/districts-filter','filterDistrict');
    });

    Route::controller(TpiController::class)->group(function () {
        Route::post('/tpi','storeTPI');
        Route::put('/tpi/{idTPI}','editTPI');
        Route::delete('/tpi/{idTPI}','removeTPI');
        Route::get('/tpi/{idTPI}','getTPI');
        Route::get('/tpi','getAllTPI');
        Route::post('/tpi/import','importTPI');
        Route::get('/tpi-filter','filterTPI');
    });

    Route::controller(UserController::class)->group(function () {
        
        Route::get('/inscriptions','getAllInscription');
        Route::put('/inscriptions/approve/{id}','inscriptionApproved');
        Route::put('/inscriptions/reject/{id}','inscriptionRejected');
        
        
        Route::get('/users','getAllUser');
        Route::get('/users/{idUser}','getUser');
    });

    Route::controller(AdminController::class)->group(function () {
        
        Route::post('/admin/logout','logout');

    });

    Route::controller(ProvisionController::class)->group(function () {
        Route::post('/provisions', 'storeCessionProvision');
        Route::get('/provisions', 'getCessionProvision');
        Route::put('/provisions/{idCessionProvision}', 'editCessionProvision');
    });
    // Route::controller(CessionController::class)->group(function () {
    //     Route::get('/ces')
    // });
});

// Routes protégées utilisateur
Route::middleware(['auth:user'])->group(function () {

    Route::middleware('role:greffier')->group(function () {
        Route::controller(CessionController::class)->group(function () {

            Route::get('/greffier/{idUser}/cessions', 'getAllCessionByGreffier');
            // --------------------------------------------------------------------------- Cession
            Route::post('/greffier/cessions', 'storeCession');
            Route::get('/greffier/cessions/{idCession}', 'getCession');
            Route::get('/greffier/cessions/{idCession}/assign', 'getCessionWithHisMagistrat');
            Route::put('/greffier/cessions/{idCession}', 'editCession');

        // --------------------------------------------------------------------------- Cession Temp
            // Route::post('/greffier/cessions/temp', 'createTempCession');
            // Route::get('/greffier/cessions/temp/{idUser}', 'getTempCession');
            // Route::delete('/greffier/cessions/temp/{idUser}', 'deleteTempCession');

            Route::post('/greffier/cessions/{idCession}/signed', 'signCession');
        });
        
        // --------------------------------------------------------------------------- Cession Justificatifs
        Route::controller(JustificatifController::class)->group(function () {
            Route::post('/greffier/cessions/{idCession}/justificatifs', 'storeCessionJustificatifs');
            Route::get('/greffier/cessions/{idCession}/justificatifs', 'getAllCessionJustificatifByCession');
            Route::get('/greffier/cessions/{idCession}/justificatifs/{idCessionJustificatif}', 'showCessionJustificatif');
            Route::delete('/greffier/cessions/{idCession}/justificatifs/{idCessionJustificatif}', 'removeCessionJustificatif');
        });

        // ------------------------------------------------------------------------------- Cession Assignment
        Route::controller(MagistratController::class)->group(function () {
            Route::post('/greffier/cessions/{idCession}/magistrats', 'storeCessionMagistrat');
            Route::put('/greffier/cessions/{idCession}/magistrats/{idCessionMagistrat}', 'editCessionMagistrat');
            Route::get('/greffier/cessions/{idCession}/magistrats', 'getMagistratByCession');
            Route::get('/greffier/tpi/{idTPI}/magistrats','getAllMagistratByTpi');

        });

        // ------------------------------------------------------------------------------- Cession Lender
        Route::controller(LenderController::class)->group(function () {
            Route::post('/greffier/cessions/{idCession}/lenders', 'storeCessionLender');
            Route::post('/greffier/cessions/{idCession}/lenders/exists', 'storeCessionLenderExists');
            Route::post('/greffier/cessions/{idCession}/lenders/exists/new-address', 'storeCessionLenderExistsNewAddress');
            Route::post('/greffier/cessions/{idCession}/lenders/entity/exists', 'storeCessionLenderEntityExists');
            Route::get('/greffier/cessions/{idCession}/lenders', 'getAllCessionLenderByCession');
            Route::put('/greffier/cessions/{idCession}/lenders/{idCessionLender}', 'editCessionLender');
            Route::delete('/greffier/cessions/{idCession}/lenders/{idCessionLender}', 'removeCessionLender');
        });

        // ------------------------------------------------------------------------------- Cession Borrower
        Route::controller(BorrowerController::class)->group(function () {
            Route::post('/greffier/cessions/{idCession}/borrowers', 'storeCessionBorrower');
            Route::post('/greffier/cessions/{idCession}/borrowers/exists', 'storeCessionBorrowerExists');
            Route::post('/greffier/cessions/{idCession}/borrowers/exists/new-address', 'storeCessionBorrowerExistsNewAddress');
            Route::get('/greffier/cessions/{idCession}/borrowers', 'getAllCessionBorrowerByCession');
            Route::get('/greffier/cessions/{idCession}/borrowers/treaty', 'getAllCessionBorrowerHaveQuotaByCession');
            Route::get('/greffier/cessions/{idCession}/borrowers/{idCessionBorrower}', 'getCessionBorrower');
            Route::put('/greffier/cessions/{idCession}/borrowers/{idCessionBorrower}', 'editCessionBorrower');
            Route::delete('/greffier/cessions/{idCession}/borrowers/{idCessionBorrower}', 'removeCessionBorrower');
            Route::get('/greffier/cessions/{idCession}/borrowers/{idCessionBorrower}/references/{idCessionReference}/export-declaration', 'generateDeclaration');
        });

        Route::controller(ReferenceController::class)->group(function () {
            Route::post('greffier/cessions/{idCession}/borrowers/{idCessionBorrower}/references', 'storeCessionReference');
            Route::put('greffier/cessions/{idCession}/borrowers/{idCessionBorrower}/references/{idCessionReference}', 'editCessionReference');
        });

        Route::controller(PersonController::class)->group(function () {
            Route::get('/cession-party/{cin}/check', 'checkCIN');
            Route::get('/cession-entity/tpi/{idTPI}', 'getEntityByTPI');
        });

    });


    Route::middleware('role:magistrat')->group(function () {


        Route::controller(MagistratController::class)->group(function () {
            Route::get('/magistrat/{idUser}/cessions', 'getAllCessionByMagistrat');
        });
                // ------------------------------------------------------------------------------- Cession Lender
        Route::controller(LenderController::class)->group(function () {
            Route::get('/magistrat/cessions/{idCession}/lenders', 'getAllCessionLenderByCession');
        });

        // ------------------------------------------------------------------------------- Cession Borrower
        Route::controller(BorrowerController::class)->group(function (): void {
            Route::get('/magistrat/cessions/{idCession}/borrowers', 'getAllCessionBorrowerByCession');
        });

        Route::controller(JustificatifController::class)->group(function () {
            Route::get('/magistrat/cessions/{idCession}/justificatifs', 'getAllCessionJustificatifByCession');
            Route::get('/magistrat/cessions/{idCession}/justificatifs/{idCessionJustificatif}', 'showCessionJustificatif');
        });

        Route::controller(BorrowerQuotaController::class)->group(function () {
            Route::post('/magistrat/cessions/{idCession}/borrowers/{idCessionBorrower}/quota', 'storeCessionBorrowerQuota');
            Route::put('/magistrat/cessions/{idCession}/borrowers/{idCessionBorrower}/quota/{idCessionBorrowerQuota}', 'editCessionBorrowerQuota');
        });

        Route::controller(OrdonnanceController::class)->group(function () {
            Route::post('/magistrat/cessions/{idCession}/ordonnance', 'storeCessionOrdonnance');
            Route::put('/magistrat/cessions/{idCession}/ordonnance/{idCessionOrdonnance}', 'editCessionOrdonnance');
        });

        Route::controller(CessionController::class)->group(function () {
            Route::get('/magistrat/cessions/{idCession}', 'getCessionWithAttributCanAccept');
            Route::post('/magistrat/cessions/{idCession}/accepted', 'acceptCession');
            Route::post('/magistrat/cessions/{idCession}/refused', 'refuseCession');
        });
    });


    Route::middleware('role:ministere')->group(function () {

        // ------------------------------------------------------------------------------- Cession Lender
        Route::controller(LenderController::class)->group(function () {
            Route::get('/ministere/cessions/{idCession}/lenders', 'getAllCessionLenderByCession');
        });

        // ------------------------------------------------------------------------------- Cession Borrower
        Route::controller(BorrowerController::class)->group(function (): void {
            Route::get('/ministere/cessions/{idCession}/borrowers', 'getAllCessionBorrowerByCession');
        });

        Route::controller(JustificatifController::class)->group(function () {
            Route::get('/ministere/cessions/{idCession}/justificatifs', 'getAllCessionJustificatifByCession');
            Route::get('/ministere/cessions/{idCession}/justificatifs/{idCessionJustificatif}', 'showCessionJustificatif');
        });

        Route::controller(CessionController::class)->group(function () {
            Route::get('/ministere/cessions/{idCession}', 'getCession');
            Route::get('/ministere/tpi/{idTPI}/cessions', 'getAllCessionByTPI');
            Route::get('/ministere/tpi/{idTPI}/cessions/filter', 'filterCessionByTPI');
            Route::get('/ministere/tpi/{idTPI}/cessions/export-excel', 'exportExcelCessionByTPI');
            Route::get('/ministere/tpi/{idTPI}/cessions/export-pdf', 'exportPdfCessionByTPI');

        });
    });

    Route::controller(AuthController::class)->group(function () {

        Route::post('/logout', 'logout');

    });

});