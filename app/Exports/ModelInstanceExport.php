<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ModelInstanceExport implements WithHeadings, WithColumnWidths
{
    public function headings(): array
    {
        return [
            'Structure parente',
            'Structure fille',
            'Province',
            'Région',
            'District',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 55,
            'B' => 45,
            'D' => 45,
            'C' => 45,
            'E' => 45,
        ];
    }
}
