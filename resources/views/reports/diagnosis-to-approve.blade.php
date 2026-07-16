<!DOCTYPE html>
<html lang="pt-BR">

<head>
<meta charset="UTF-8">

<style>

    @font-face {
        font-family: 'Nunito';
        font-weight: 400;
        font-style: normal;
        src: url('{{ str_replace('\\', '/', resource_path('fonts/nunito/Nunito-Regular.ttf')) }}') format('truetype');
    }

    @font-face {
        font-family: 'Nunito';
        font-weight: 600;
        font-style: normal;
        src: url('{{ str_replace('\\', '/', resource_path('fonts/nunito/Nunito-SemiBold.ttf')) }}') format('truetype');
    }

    @font-face {
        font-family: 'Nunito';
        font-weight: 700;
        font-style: normal;
        src: url('{{ str_replace('\\', '/', resource_path('fonts/nunito/Nunito-Bold.ttf')) }}') format('truetype');
    }

    @font-face {
        font-family: 'Nunito';
        font-weight: 800;
        font-style: normal;
        src: url('{{ str_replace('\\', '/', resource_path('fonts/nunito/Nunito-ExtraBold.ttf')) }}') format('truetype');
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: "Nunito", sans-serif;
        font-size: 13px;
        color: #111111;
        margin: 0;
        padding: 20px 26px;
        line-height: 1.45;
    }

    /* Document sections */

    .doc-section {
        padding: 14px 0;
    }

    .doc-section + .doc-section {
        border-top: 1px solid #d8d8d8;
    }

    .doc-header {
        padding-bottom: 14px;
        border-bottom: 2px solid #0a0a0a;
    }

    /* Header */

    table.header-table {
        width: 100%;
        border-collapse: collapse;
    }

    table.header-table td {
        vertical-align: middle;
        padding: 0;
    }

    table.header-table td.header-right {
        text-align: right;
    }

    .workshop-name {
        font-size: 17px;
        font-weight: 800;
        letter-spacing: -0.2px;
    }

    .workshop-phone {
        color: #6b6b6b;
        font-size: 12px;
        margin-top: 3px;
    }

    .doc-kicker {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #9a9a9a;
    }

    .doc-title {
        font-size: 15px;
        font-weight: 800;
        margin-top: 2px;
    }

    /* Section title */

    .section-title {
        font-weight: 800;
        font-size: 11px;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #6b6b6b;
        margin-bottom: 10px;
    }

    /* Classic grid tables */

    table.grid {
        width: 100%;
        border-collapse: collapse;
    }

    table.grid th,
    table.grid td {
        border: 1px solid #d8d8d8;
        padding: 8px 10px;
        text-align: left;
        vertical-align: top;
    }

    table.grid th {
        width: 15%;
        background: #f5f5f5;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #6b6b6b;
    }

    table.grid td {
        width: 35%;
        font-size: 13px;
        font-weight: 600;
        color: #0a0a0a;
    }

    /* Services table */

    table.services {
        width: 100%;
        border-collapse: collapse;
        page-break-inside: auto;
    }

    table.services thead {
        display: table-header-group;
    }

    table.services tr {
        page-break-inside: avoid;
    }

    table.services th {
        background: #0a0a0a;
        color: #ffffff;
        text-align: left;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        padding: 8px 10px;
        border: 1px solid #0a0a0a;
    }

    table.services td {
        padding: 8px 10px;
        border: 1px solid #d8d8d8;
        font-size: 13px;
    }

    table.services tfoot td {
        border: 1px solid #d8d8d8;
        border-top: 2px solid #0a0a0a;
        background: #f5f5f5;
        padding: 10px;
    }

    table.services tfoot .total-label-cell {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: #444444;
    }

    table.services tfoot .total-value-cell {
        font-size: 17px;
        font-weight: 800;
        color: #0a0a0a;
    }

    .text-right {
        text-align: right;
    }

    /* Signatures */

    .signatures {
        width: 100%;
    }

    .signature {
        width: 48%;
        display: inline-block;
        text-align: center;
    }

    .signature-line {
        margin-top: 44px;
        border-top: 1px solid #0a0a0a;
        padding-top: 6px;
        font-size: 11px;
        color: #6b6b6b;
    }

    /* Footer */

    .autosy-footer {
        margin-top: 18px;
        text-align: center;
        font-size: 11px;
        color: #9a9a9a;
    }

    .autosy-brand {
        font-weight: 800;
        font-size: 12px;
        color: #0a0a0a;
        margin-bottom: 3px;
    }

</style>

</head>

<body>

<div class="doc-section doc-header">
    <table class="header-table">
        <tr>
            <td class="header-left">
                <div class="workshop-name">{{ $workshop['name'] }}</div>
                <div class="workshop-phone">{{ $workshop['phone_number'] }}</div>
            </td>
            <td class="header-right">
                <div class="doc-kicker">Orçamento</div>
                <div class="doc-title">Diagnóstico para aprovação</div>
            </td>
        </tr>
    </table>
</div>

    <div class="doc-section">

        <div class="section-title">
            Cliente e veículo
        </div>

        <table class="grid">

            <tr>
                <th>Cliente</th>
                <td>{{ $customer['name'] }}</td>
                <th>Telefone</th>
                <td>{{ $customer['phone_number'] }}</td>
            </tr>

            <tr>
                <th>Placa</th>
                <td>{{ $license_plate }}</td>
                <th>Modelo</th>
                <td>{{ $vehicle['model'] }}</td>
            </tr>

            @if(!empty($notes))
                <tr>
                    <th>Observações</th>
                    <td colspan="3">{{ $notes }}</td>
                </tr>
            @endif

        </table>

    </div>

    <div class="doc-section">

        <div class="section-title">
            Serviços do orçamento
        </div>

        <table class="services">

            <thead>
            <tr>
                <th>Serviço</th>
                <th class="text-right">Qtd</th>
                <th class="text-right">Valor</th>
                <th class="text-right">Total</th>
            </tr>
            </thead>

            <tbody>

            @php
                $total = 0;
            @endphp

            @foreach ($services as $service)

                @php
                    $line = $service['unit_price'] * $service['quantity'];
                    $total += $line;
                @endphp

                <tr>

                    <td>
                        {{ $service['service_name'] }}
                    </td>

                    <td class="text-right">
                        {{ $service['quantity'] }}
                    </td>

                    <td class="text-right">
                        R$ {{ number_format($service['unit_price'], 2, ',', '.') }}
                    </td>

                    <td class="text-right">
                        R$ {{ number_format($line, 2, ',', '.') }}
                    </td>

                </tr>

            @endforeach

            </tbody>

            <tfoot>
            <tr>
                <td colspan="3" class="text-right total-label-cell">Valor total</td>
                <td class="text-right total-value-cell">R$ {{ number_format($total, 2, ',', '.') }}</td>
            </tr>
            </tfoot>

        </table>

    </div>

    <div class="doc-section">

        <div class="signatures">

            <div class="signature">
                <div class="signature-line">
                    Assinatura da Oficina
                </div>
            </div>

            <div class="signature">
                <div class="signature-line">
                    Assinatura do Cliente
                </div>
            </div>

        </div>

    </div>

<div class="autosy-footer">

    <div class="autosy-brand">
        Autosy
    </div>

    <div>
        Sistema de gestão para oficinas
    </div>

    <div>
        www.autosy.com.br
    </div>

</div>

</body>
</html>
