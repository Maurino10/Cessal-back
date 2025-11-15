<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport des Cessions</title>
    <style>
        @page { size: landscape; margin: 10mm; }
        body { margin: 0; padding: 0; font-family: sans-serif; }
        h2 { text-align: center; margin: 20px 0 10px; }
        table { width: 100%; border-collapse: collapse; font-size: 11px; margin-bottom: 30px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; font-weight: bold; }
        tr, td, th { page-break-inside: avoid; }
        tbody { page-break-inside: avoid; }
        .lender-row { background-color: #dbeafe; }
        .borrower-row { background-color: #fef3c7; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .nowrap { white-space: nowrap; }
    </style>
</head>
<body>

    <h1 class="text-center">Ministère de la Justice de Madagascar</h1>
    <div>
        <p>Statut : {{ $statut }}</p>
        <p>Date début : {{ $dateStart }}</p>
        <p>Date fin : {{ $dateEnd }}</p>
    </div>

    @php
        // ✅ Grouper les cessions par TPI
        $groupedCessions = $cessions->groupBy(fn($c) => $c['tpi']['name'] ?? 'TPI non défini');
    @endphp

    {{-- ✅ Un tableau par TPI --}}
    @foreach($groupedCessions as $tpiName => $cessionsByTPI)
        <h2>{{ $cessionsByTPI[0]['tpi']['ca']['name'] ." - ". $cessionsByTPI[0]['tpi']['name'] }}</h2>
        <table>
            <thead>
                <tr>
                    <th width="5%">N° dossier</th>
                    <th width="15%">Objet</th>
                    <th width="6%">Montant demandé</th>
                    <th width="6%">Date de cession</th>
                    <th width="6%" class="text-center">TPI</th>
                    <th width="5%">Type</th>
                    <th width="12%">Nom</th>
                    <th width="12%">Prénom</th>
                    <th width="8%">Montant Revenu</th>
                    <th width="8%">Montant accordé</th>
                    <th width="5%">Pourcentage %</th>
                </tr>
            </thead>

            {{-- ✅ Un <tbody> par cession --}}
            @foreach($cessionsByTPI as $cession)
                @php
                    $nbLenders = count($cession['lenders'] ?? []);
                    $nbBorrowers = count($cession['borrowers'] ?? []);
                    $rowspan = max($nbLenders + $nbBorrowers, 1);
                    $firstRow = true;
                @endphp

                <tbody style="page-break-inside: avoid;">
                    {{-- Prêteurs --}}
                    @forelse($cession['lenders'] ?? [] as $lender)
                        <tr>
                            @if($firstRow)
                                <td rowspan="{{ $rowspan }}" class="text-center">{{ $cession['numero_dossier'] ?? '-' }}</td>
                                <td rowspan="{{ $rowspan }}">{{ $cession['request_subject'] ?? '-' }}</td>
                                <td rowspan="{{ $rowspan }}" class="text-right nowrap">
                                    {{ isset($cession['reimbursed_amount']) ? number_format($cession['reimbursed_amount'], 0, ',', ' ') . ' Ar' : '-' }}
                                </td>
                                <td rowspan="{{ $rowspan }}" class="text-center nowrap">
                                    {{ isset($cession['date_cession']) ? date('d/m/Y', strtotime($cession['date_cession'])) : '-' }}
                                </td>
                                <td rowspan="{{ $rowspan }}">{{ $cession['tpi']['name'] ?? '-' }}</td>
                                @php $firstRow = false; @endphp
                            @endif
                            <td class="lender-row">Prêteur</td>
                            @if($lender['id_cession_natural_person'])
                                <td class="lender-row">{{ $lender['naturalPerson']['last_name'] ?? '-' }}</td>
                                <td class="lender-row">{{ $lender['naturalPerson']['first_name'] ?? '-' }}</td>
                            @endif
                            @if($lender['id_cession_legal_person'])
                                <td class="lender-row">{{ $lender['legalPerson']['name'] ?? '-' }}</td>
                                <td class="lender-row">{{ $lender['legalPerson']['address'] ?? '-' }}</td>
                            @endif
                            <td class="text-right lender-row"></td>
                            <td class="text-right lender-row"></td>
                            <td class="text-right lender-row"></td>
                        </tr>
                    @empty
                        <tr><td colspan="11" class="text-center">Aucun prêteur</td></tr>
                    @endforelse

                    {{-- Emprunteurs --}}
                    @forelse($cession['borrowers'] ?? [] as $borrower)
                        <tr class="borrower-row">
                            @if($firstRow)
                                <td rowspan="{{ $rowspan }}" class="text-center">{{ $cession['numero_dossier'] ?? '-' }}</td>
                                <td rowspan="{{ $rowspan }}">{{ $cession['request_subject'] ?? '-' }}</td>
                                <td rowspan="{{ $rowspan }}" class="text-right nowrap">
                                    {{ isset($cession['reimbursed_amount']) ? number_format($cession['reimbursed_amount'], 0, ',', ' ') . ' Ar' : '-' }}
                                </td>
                                <td rowspan="{{ $rowspan }}" class="text-center nowrap">
                                    {{ isset($cession['date_cession']) ? date('d/m/Y', strtotime($cession['date_cession'])) : '-' }}
                                </td>
                                <td rowspan="{{ $rowspan }}">{{ $cession['tpi']['name'] ?? '-' }}</td>
                                @php $firstRow = false; @endphp
                            @endif
                            <td>Emprunteur</td>
                            <td>{{ $borrower['naturalPerson']['last_name'] ?? '-' }}</td>
                            <td>{{ $borrower['naturalPerson']['first_name'] ?? '-' }}</td>
                            <td class="text-right nowrap">
                                {{ isset($borrower['salary_amount']) ? number_format($borrower['salary_amount'], 0, ',', ' ') . ' Ar' : '-' }}
                            </td>
                            <td class="text-right nowrap">
                                {{ isset($borrower['quota']['granted_amount']) ? number_format($borrower['quota']['granted_amount'], 0, ',', ' ') . ' Ar' : '-' }}
                            </td>
                            <td class="text-right nowrap">
                                {{ isset($borrower['quota']['percentage']) ? number_format($borrower['quota']['percentage'], 0, ',', ' ') . ' %' : '-' }}
                            </td>
                        </tr>
                    @empty
                        @if($nbLenders === 0)
                            <tr><td colspan="11" class="text-center">Aucun emprunteur</td></tr>
                        @endif
                    @endforelse
                </tbody>
            @endforeach
        </table>
    @endforeach
</body>
</html>
