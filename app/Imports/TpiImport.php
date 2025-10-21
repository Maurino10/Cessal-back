<?php

namespace App\Imports;

use App\Models\Temps\TempTpi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TpiImport implements ToCollection, WithHeadingRow, WithMultipleSheets
{
    public function collection(Collection $rows)
    {
            
        $filteredRows = $rows->filter(function ($row) {
            return 
                isset($row["structure_fille"]) && 
                str_starts_with(strtolower(trim($row["structure_fille"])), "tpi");
        });

        foreach ($filteredRows as $row) { 
            TempTpi::create([
                "structure_parente"=> $row["structure_parente"],
                "structure_fille"=> $row["structure_fille"],
                "province"=> $row["province"],
                "region"=> $row["region"],
                "district"=> $row["district"],
            ]);
        }

        DB::statement("SELECT treatment_temp_tpi()");
    }
    
    public function sheets(): array
    {
        return [
            0 => $this, // Seulement la première feuille (index 0)
        ];
    }
    // Optionnel: définir la ligne d'en-tête
    public function headingRow(): int
    {
        return 1; // La première ligne contient les en-têtes
    }
}
