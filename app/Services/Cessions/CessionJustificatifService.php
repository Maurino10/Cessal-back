<?php

namespace App\Services\Cessions;

use App\Models\Cessions\CessionJustificatif;
use Illuminate\Support\Facades\Storage;

class CessionJustificatifService
{

    public function saveCessionJustificatif($name, $path, $type, $size, $idCession)
    {
        $justif = CessionJustificatif::create([
            'name' => $name,
            'path' => $path,
            'type' => $type,
            'size' => $size,
            'id_cession' => $idCession
        ]);

        return $justif;
    }

    public function findAllCessionJustificatifByCession($idCession) {
        $justifs = CessionJustificatif::where('id_cession', $idCession)->get();

        return $justifs;
    }
    public function findCessionJustificatif($idCession, $idCessionJustificatif)
    {
        $justif = CessionJustificatif::where([
            ['id', $idCessionJustificatif],
            ['id_cession', $idCession]
        ])->first();

        return $justif;
    }

    public function deleteCessionJustificatif($idCession, $idCessionJustificatif)
    {
        $justif = CessionJustificatif::where([
            ['id', $idCessionJustificatif],
            ['id_cession', $idCession]
        ])->first();

        if (Storage::disk('public')->exists($justif->path)) {
            Storage::disk('public')->delete($justif->path);
        }

        $justif->delete();
    }
}