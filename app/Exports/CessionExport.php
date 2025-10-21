<?php

namespace App\Exports;

use App\Models\Cessions\Cession;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class CessionExport implements FromView
{
    protected $cessions;
    public function __construct($cessions) {
        $this->cessions = $cessions;
    }
    public function view(): View
    {
        return view('cessions.cession-excel', [
            'cessions' => $this->cessions
        ]);
    }
}
