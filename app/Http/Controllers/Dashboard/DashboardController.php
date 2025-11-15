<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService) {
        $this->dashboardService = $dashboardService;
    }

    public function getDashboard(Request $request) {
        $dateStart = $request->input('dateStart');
        $dateEnd = $request->input('dateEnd');

        $statCessionCA = $this->dashboardService->statCessionCA($dateStart, $dateEnd);
        $statCessionGlobal = $this->dashboardService->statCessionGlobal($dateStart, $dateEnd);
        

        return response()->json([
            'statCessionCA' => $statCessionCA,
            'statCessionGlobal' => $statCessionGlobal,
        ]);
    }
    
    public function getDetailsCa($idCA, Request $request) {
        $dateStart = $request->input('dateStart');
        $dateEnd = $request->input('dateEnd');

        $details = $this->dashboardService->statCessionByCA($idCA, $dateStart, $dateEnd);
        $tpis = $this->dashboardService->statCessionTPIByCA($idCA, $dateStart, $dateEnd);

        return response()->json([
            'details' => $details,
            'tpis' => $tpis,
        ]);
    }   
}
