<?php

namespace App\Services\Dashboard;

use Illuminate\Support\Facades\DB;

class DashboardService {

    public function statCessionGlobal($dateStart, $dateEnd) {

        $query = DB::table(DB::raw('cession'))
            ->select(
                DB::raw('SUM(CASE WHEN status_cession = 0 THEN 1 ELSE 0 END) AS save'),
                DB::raw('SUM(CASE WHEN status_cession = 1 THEN 1 ELSE 0 END) AS waiting'),
                DB::raw('SUM(CASE WHEN status_cession = 2 AND signed = 0 THEN 1 ELSE 0 END) AS approved'),
                DB::raw('SUM(CASE WHEN status_cession = 3 THEN 1 ELSE 0 END) AS rejected'),
                DB::raw('SUM(CASE WHEN status_cession = 2 AND signed = 1 THEN 1 ELSE 0 END) AS signed'),
                DB::raw('
                    (
                        SUM(CASE WHEN status_cession = 0 THEN 1 ELSE 0 END) +
                        SUM(CASE WHEN status_cession = 1 THEN 1 ELSE 0 END) +
                        SUM(CASE WHEN status_cession = 2 AND signed = 0 THEN 1 ELSE 0 END) +
                        SUM(CASE WHEN status_cession = 3 THEN 1 ELSE 0 END) +
                        SUM(CASE WHEN status_cession = 2 AND signed = 1 THEN 1 ELSE 0 END)
                    ) AS total
                ')
            );

        if ($dateStart !== 'null' && $dateEnd == 'null') {
            $query->where('date_cession', '>=', $dateStart);
        }

        if ($dateStart == 'null' && $dateEnd !== 'null') {
            $query->where('date_cession', '<=', $dateEnd);
        }

        if ($dateStart !== 'null' && $dateEnd !== 'null') {
            $query->whereBetween('date_cession', [$dateStart, $dateEnd]);
        }
            

        return $query
            ->orderByDesc('total')
            ->first();
    }
    public function statCessionCA($dateStart, $dateEnd) {
        $query = DB::table('ca')
            ->select(
                'ca.id as id_ca',
                'ca.name',
                DB::raw("COUNT(DISTINCT tpi.id) AS total_tpi"),
                DB::raw("
                    (
                        SUM(CASE WHEN c.status_cession = 0 THEN 1 ELSE 0 END) +
                        SUM(CASE WHEN c.status_cession = 1 THEN 1 ELSE 0 END) +
                        SUM(CASE WHEN c.status_cession = 2 AND c.signed = 0 THEN 1 ELSE 0 END) +
                        SUM(CASE WHEN c.status_cession = 3 THEN 1 ELSE 0 END) +
                        SUM(CASE WHEN c.status_cession = 2 AND c.signed = 1 THEN 1 ELSE 0 END)
                    ) AS total
                ")
            )
            ->leftJoin('tpi', 'ca.id', '=', 'tpi.id_ca')
            ->leftJoin('cession as c', 'tpi.id', '=', 'c.id_tpi');
        
        
        if ($dateStart !== 'null' && $dateEnd == 'null') {
            $query->where('c.date_cession', '>=', $dateStart);
        }

        if ($dateStart == 'null' && $dateEnd !== 'null') {
            $query->where('c.date_cession', '<=', $dateEnd);
        }

        if ($dateStart !== 'null' && $dateEnd !== 'null') {
            $query->whereBetween('c.date_cession', [$dateStart, $dateEnd]);
        }
            
            

        return $query
            ->groupBy('ca.id', 'ca.name')
            ->orderByDesc('total')
            ->get();
    }

    public function statCessionByCA($idCA, $dateStart, $dateEnd) {
        $query = DB::table('ca')
            ->select(
                'ca.id as id_ca',
                'ca.name',

                DB::raw("SUM(CASE WHEN c.status_cession = 0 THEN 1 ELSE 0 END) AS save"),
                DB::raw("SUM(CASE WHEN c.status_cession = 1 THEN 1 ELSE 0 END) AS waiting"),
                DB::raw("SUM(CASE WHEN c.status_cession = 2 AND c.signed = 0 THEN 1 ELSE 0 END) AS approved"),
                DB::raw("SUM(CASE WHEN c.status_cession = 3 THEN 1 ELSE 0 END) AS rejected"),
                DB::raw("SUM(CASE WHEN c.status_cession = 2 AND c.signed = 1 THEN 1 ELSE 0 END) AS signed"),

                DB::raw("
                    (
                        SUM(CASE WHEN c.status_cession = 0 THEN 1 ELSE 0 END) +
                        SUM(CASE WHEN c.status_cession = 1 THEN 1 ELSE 0 END) +
                        SUM(CASE WHEN c.status_cession = 2 AND c.signed = 0 THEN 1 ELSE 0 END) +
                        SUM(CASE WHEN c.status_cession = 3 THEN 1 ELSE 0 END) +
                        SUM(CASE WHEN c.status_cession = 2 AND c.signed = 1 THEN 1 ELSE 0 END)
                    ) AS total
                ")
            )
            ->leftJoin('tpi', 'ca.id', '=', 'tpi.id_ca')
            ->leftJoin('cession as c', 'tpi.id', '=', 'c.id_tpi')
            ->where('ca.id', $idCA);

        if ($dateStart !== 'null' && $dateEnd == 'null') {
            $query->where('c.date_cession', '>=', $dateStart);
        }

        if ($dateStart == 'null' && $dateEnd !== 'null') {
            $query->where('c.date_cession', '<=', $dateEnd);
        }

        if ($dateStart !== 'null' && $dateEnd !== 'null') {
            $query->whereBetween('c.date_cession', [$dateStart, $dateEnd]);
        }

        return $query
            ->groupBy('ca.id', 'ca.name')
            ->orderByDesc('total')
            ->first();
    }

    public function statCessionTPIByCA($idCA, $dateStart, $dateEnd) {

        $query = DB::table(DB::raw('tpi'))
            ->select(
                'tpi.id',
                'tpi.name',
                DB::raw('SUM(CASE WHEN c.status_cession = 0 THEN 1 ELSE 0 END) AS save'),
                DB::raw('SUM(CASE WHEN c.status_cession = 1 THEN 1 ELSE 0 END) AS waiting'),
                DB::raw('SUM(CASE WHEN c.status_cession = 2 AND c.signed = 0 THEN 1 ELSE 0 END) AS approved'),
                DB::raw('SUM(CASE WHEN c.status_cession = 3 THEN 1 ELSE 0 END) AS rejected'),
                DB::raw('SUM(CASE WHEN c.status_cession = 2 AND c.signed = 1 THEN 1 ELSE 0 END) AS signed'),
                DB::raw('
                    (
                        SUM(CASE WHEN c.status_cession = 0 THEN 1 ELSE 0 END) +
                        SUM(CASE WHEN c.status_cession = 1 THEN 1 ELSE 0 END) +
                        SUM(CASE WHEN c.status_cession = 2 AND c.signed = 0 THEN 1 ELSE 0 END) +
                        SUM(CASE WHEN c.status_cession = 3 THEN 1 ELSE 0 END) +
                        SUM(CASE WHEN c.status_cession = 2 AND c.signed = 1 THEN 1 ELSE 0 END)
                    ) AS total
                ')
            )
            ->leftJoin('cession AS c', 'tpi.id', '=', 'c.id_tpi')
            ->where('tpi.id_ca', $idCA);

        if ($dateStart !== 'null' && $dateEnd == 'null') {
            $query->where('c.date_cession', '>=', $dateStart);
        }

        if ($dateStart == 'null' && $dateEnd !== 'null') {
            $query->where('c.date_cession', '<=', $dateEnd);
        }

        if ($dateStart !== 'null' && $dateEnd !== 'null') {
            $query->whereBetween('c.date_cession', [$dateStart, $dateEnd]);
        }

        return $query 
            ->groupBy('tpi.id', 'tpi.name')
            ->orderByDesc('total')
            ->get();;
    }


}