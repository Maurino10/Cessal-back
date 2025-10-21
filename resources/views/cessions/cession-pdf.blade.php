<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport des Cessions</title>
    <style>
        @page {
            size: landscape;
            margin: 10mm;
        }
        body {
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .lender-row {
            background-color: #dbeafe;
        }
        .borrower-row {
            background-color: #fef3c7;
        }
        .header-cell {
            background-color: #e5e7eb;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .nowrap {
            white-space: nowrap;
        }
    </style>
</head>
<body>  
    <h1 class="text-center" >{{ $ca->name." - ".$tpi->name }}</h1>
    <div>
        <p>Statut : {{ $statut }}</p>
        <p>Date début : {{ $dateStart }}</p>
        <p>Date fin : {{ $dateEnd }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">N° dossier</th>
                <th width="15%">Objet</th>
                <th width="6%">Montant demandé</th>
                <th width="6%">Date de cession</th>
                <th width="5%">Type</th>
                <th width="12%">Nom</th>
                <th width="12%">Prénom</th>
                <th width="8%">Montant Revenu</th>
                <th width="8%">Montant accordé</th>
                <th width="5%">Pourcentage %</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cessions as $cession)
                @php
                    $nbLenders = count($cession['lenders'] ?? []);
                    $nbBorrowers = count($cession['borrowers'] ?? []);
                    $rowspan = max($nbLenders + $nbBorrowers, 1);
                    $firstRow = true;
                @endphp

                {{-- Affichage des demandeurs --}}
                @forelse($cession['lenders'] ?? [] as $lender)
                    <tr class="lender-row">
                        @if($firstRow)
                            <td rowspan="{{ $rowspan }}" class="text-center" style="vertical-align: middle;">
                                {{ $cession['numero_dossier'] ?? '-' }}
                            </td>
                            <td rowspan="{{ $rowspan }}" style="word-wrap: break-word; vertical-align: middle;">
                                {{ $cession['request_subject'] ?? '-' }}
                            </td>
                            <td rowspan="{{ $rowspan }}" class="text-right nowrap" style="vertical-align: middle;">
                                {{ isset($cession['reimbursed_amount']) ? number_format($cession['reimbursed_amount'], 0, ',', ' ') . ' Ar' : '-' }}
                            </td>
                            <td rowspan="{{ $rowspan }}" class="text-center nowrap" style="vertical-align: middle;">
                                {{ isset($cession['date_cession']) ? date('d/m/Y', strtotime($cession['date_cession'])) : '-' }}
                            </td>
                            @php $firstRow = false; @endphp
                        @endif
                        <td>Demandeur</td>
                        <td>{{ $lender['party']['last_name'] ?? '-' }}</td>
                        <td>{{ $lender['party']['first_name'] ?? '-' }}</td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                    </tr>
                @empty
                    {{-- Si pas de demandeurs, afficher une ligne vide --}}
                    @if($firstRow)
                        <tr>
                            <td class="text-center">{{ $cession['numero_dossier'] ?? '-' }}</td>
                            <td>{{ $cession['request_subject'] ?? '-' }}</td>
                            <td class="text-right nowrap">
                                {{ isset($cession['reimbursed_amount']) ? number_format($cession['reimbursed_amount'], 0, ',', ' ') . ' Ar' : '-' }}
                            </td>
                            <td class="text-center nowrap">
                                {{ isset($cession['date_cession']) ? date('d/m/Y', strtotime($cession['date_cession'])) : '-' }}
                            </td>
                            <td colspan="6" class="text-center">Aucun demandeur ou défendeur</td>
                        </tr>
                        @php $firstRow = false; @endphp
                    @endif
                @endforelse

                {{-- Affichage des défendeurs --}}
                @forelse($cession['borrowers'] ?? [] as $borrower)
                    <tr class="borrower-row">
                        @if($firstRow)
                            <td rowspan="{{ $rowspan }}" class="text-center" style="vertical-align: middle;">
                                {{ $cession['numero_dossier'] ?? '-' }}
                            </td>
                            <td rowspan="{{ $rowspan }}" style="word-wrap: break-word; vertical-align: middle;">
                                {{ $cession['request_subject'] ?? '-' }}
                            </td>
                            <td rowspan="{{ $rowspan }}" class="text-right nowrap" style="vertical-align: middle;">
                                {{ isset($cession['reimbursed_amount']) ? number_format($cession['reimbursed_amount'], 0, ',', ' ') . ' Ar' : '-' }}
                            </td>
                            <td rowspan="{{ $rowspan }}" class="text-center nowrap" style="vertical-align: middle;">
                                {{ isset($cession['date_cession']) ? date('d/m/Y', strtotime($cession['date_cession'])) : '-' }}
                            </td>
                            @php $firstRow = false; @endphp
                        @endif
                        <td>Défendeur</td>
                        <td>{{ $borrower['party']['last_name'] ?? '-' }}</td>
                        <td>{{ $borrower['party']['first_name'] ?? '-' }}</td>
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
                    {{-- Gestion si pas de défendeurs mais des demandeurs existent --}}
                    @if($nbLenders === 0 && $firstRow)
                        <tr>
                            <td class="text-center">{{ $cession['numero_dossier'] ?? '-' }}</td>
                            <td>{{ $cession['request_subject'] ?? '-' }}</td>
                            <td class="text-right nowrap">
                                {{ isset($cession['reimbursed_amount']) ? number_format($cession['reimbursed_amount'], 0, ',', ' ') . ' Ar' : '-' }}
                            </td>
                            <td class="text-center nowrap">
                                {{ isset($cession['date_cession']) ? date('d/m/Y', strtotime($cession['date_cession'])) : '-' }}
                            </td>
                            <td colspan="6" class="text-center">Aucun demandeur ou défendeur</td>
                        </tr>
                    @endif
                @endforelse
            @endforeach
        </tbody>
    </table>
</body>
</html>