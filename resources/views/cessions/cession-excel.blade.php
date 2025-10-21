<table border="1">
    <thead>
        <tr>
            <th>Numéro dossier</th>
            <th>Objet</th>
            <th>Montant demandé</th>
            <th>Date de cession</th>
            <th>Type</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Montant Revenu</th>
            <th>Montant accordé</th>
            <th>Pourcentage %</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cessions as $cession)
            @php
                $nbLenders = count($cession['lenders']);
                $nbBorrowers = count($cession['borrowers']);
                $rowspan = $nbLenders + $nbBorrowers;
                $firstRow = true;
            @endphp

            {{-- Affichage des demandeurs --}}
            @foreach($cession['lenders'] as $lender)
                <tr>
                    @if($firstRow)
                        <td width="20" height="10" valign="center" rowspan="{{ $rowspan }}">{{ $cession['numero_dossier'] }}</td>
                        <td width="50" height="10" valign="center" style="word-wrap: break-word;" rowspan="{{ $rowspan }}">{{ $cession['request_subject'] }}</td>
                        <td width="20" height="10" valign="center" style="text-align: right;" rowspan="{{ $rowspan }}">{{ number_format($cession['reimbursed_amount'], 0, ',', ' ') }} Ar</td>
                        <td width="20" height="10" valign="center" style="text-align: center;" rowspan="{{ $rowspan }}">{{ date("d/m/Y", strtotime($cession['date_cession'])) }}</td>

                        @php $firstRow = false; @endphp
                    @endif
                    <td width="15" height="15" bgcolor="#dbeafe">Demandeur</td>
                    <td width="50" height="15" bgcolor="#dbeafe">{{ $lender['party']['last_name'] }}</td>
                    <td width="50" height="15" bgcolor="#dbeafe">{{ $lender['party']['first_name'] }}</td>
                    <td width="25" height="15" bgcolor="#dbeafe"></td>
                    <td width="25" height="15" bgcolor="#dbeafe"></td>
                    <td width="15" height="15" bgcolor="#dbeafe"></td>
                </tr>
            @endforeach

            {{-- Affichage des défendeurs --}}
            @foreach($cession['borrowers'] as $borrower)
                <tr>
                    <td width="15" height="15" bgcolor="#fef3c7">Défendeur</td>
                    <td width="50" height="15" style="word-wrap: break-word;" bgcolor="#fef3c7">{{ $borrower['party']['last_name'] }}</td>
                    <td width="50" height="15" style="word-wrap: break-word;" bgcolor="#fef3c7">{{ $borrower['party']['first_name'] }}</td>
                    <td width="25" height="15" bgcolor="#fef3c7" style="text-align: right;">{{ number_format($borrower['salary_amount'], 0, ',', ' ') }} Ar</td>
                    <td width="25" height="15" bgcolor="#fef3c7" style="text-align: right;">
                        @if(isset($borrower['quota']['granted_amount']))
                            {{ number_format($borrower['quota']['granted_amount'], 0, ',', ' ') }} Ar
                        @else
                            {{ '-' }}
                        @endif
                    </td>
                    <td width="15" height="15" bgcolor="#fef3c7" style="text-align: right;">
                        @if(isset($borrower['quota']['percentage']))
                            {{ number_format($borrower['quota']['percentage'], 0, ',', ' ') }} %
                        @else
                            {{ '-' }}
                        @endif
                    </td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
