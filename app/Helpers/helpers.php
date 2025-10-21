<?php

use Carbon\Carbon;
use NumberToWords\NumberToWords;

function spell_money_ariary($amount)
{
    $numberToWords = new NumberToWords();
    $numberTransformer = $numberToWords->getNumberTransformer('fr');

    // Séparer la partie entière et les centimes
    $parts = explode('.', number_format($amount, 2, '.', ''));
    $entier = (int)$parts[0];
    $centimes = (int)$parts[1];

    $result = ucfirst($numberTransformer->toWords($entier)) . ' ariary';
    if ($centimes > 0) {
        $result .= ' et ' . $numberTransformer->toWords($centimes) . ' centimes';
    }

    return $result;
}


function appelation($person) {
    $appelations = [
        'Homme' => 'Monsieur',
        'Femme' => 'Madame'
    ];

    return $appelations[$person->gender->name] .' '. $person->last_name .' '. $person->first_name;
}

function formattedDate ($date) {
    // Créer un objet Carbon
    $carbonDate = Carbon::parse($date);

    // Formatter en français
    $formatted = $carbonDate->locale('fr')->isoFormat('D MMM YYYY');

    return $formatted; // 21 sept 2025
}