<!DOCTYPE html>
<html lang="pt-BR">

<head>
<meta charset="UTF-8">

<style>

    @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap');
    body {
        font-family: "Nunito Sans", sans-serif;
        font-size: 14px;
        color: #111;
        margin: 15px;
    }

    .header {
        margin-bottom: 30px;
    }

    .workshop-name {
        font-size: 24px;
        font-weight: 700;
    }

    .workshop-phone {
        color: #666;
        margin-top: 3px;
    }

    .card {
        border: 1px solid #e8e8e8;
        border-radius: 10px;
        padding: 18px;
        margin-bottom: 20px;
    }

    .card-title {
        font-weight: 700;
        font-size: 16px;
        margin-bottom: 15px;
    }

    .row {
        width: 100%;
    }

    .col {
        width: 49%;
        display: inline-block;
        vertical-align: top;
    }

    .label {
        font-size: 12px;
        color: #777;
    }

    .value {
        font-weight: 600;
        margin-top: 3px;
    }

    .observation {
        margin-top: 15px;
        background: #f7f7f7;
        padding: 12px;
        border-radius: 8px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        page-break-inside: auto;
    }

    thead {
        display: table-header-group;
    }

    tr {
        page-break-inside: avoid;
    }

    th {
        text-align: left;
        font-size: 12px;
        color: #666;
        border-bottom: 1px solid #ddd;
        padding-bottom: 8px;
    }

    td {
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }

    .text-right {
        text-align: right;
    }

    .total-card {
        text-align: right;
        page-break-inside: avoid;
    }

    .total-label {
        font-size: 14px;
        color: #555;
    }

    .total-value {
        font-size: 24px;
        font-weight: 700;
        margin-top: 4px;
    }

    .signatures {
        margin-top: 80px;
    }

    .signature {
        width: 48%;
        display: inline-block;
        text-align: center;
    }

    .signature-line {
        margin-top: 70px;
        border-top: 1px solid #000;
        padding-top: 8px;
    }

    .autosy-footer {
        margin-top: 40px;
        text-align: center;
        font-size: 12px;
        color: #777;
    }

    .autosy-brand {
        font-weight: 700;
        font-size: 14px;
        margin-bottom: 5px;
    }

</style>

</head>

<body>

<div class="header">

    <div class="workshop-name">
        {{ $workshop['name'] }}
    </div>

    <div class="workshop-phone">
        Telefone: {{ $workshop['phone_number'] }}
    </div>

</div>


<div class="card">

    <div class="card-title">
        Dados do cliente
    </div>

    <div class="row">

        <div class="col">
            <div class="label">Cliente</div>
            <div class="value">
                {{ $customer['name'] }}
            </div>
        </div>

        <div class="col">
            <div class="label">Telefone</div>
            <div class="value">
                {{ $customer['phone_number'] }}
            </div>
        </div>

    </div>

</div>


<div class="card">

    <div class="card-title">
        Dados do veículo
    </div>

    <div class="row">

        <div class="col">
            <div class="label">Placa</div>
            <div class="value">
                {{ $license_plate }}
            </div>
        </div>

        <div class="col">
            <div class="label">Modelo</div>
            <div class="value">
                {{ $vehicle['model'] }}
            </div>
        </div>

    </div>

    <div class="observation">

        <div class="label">Observações</div>

        <div class="value">
            {{ $notes }}
        </div>

    </div>

</div>


<div class="card">

    <div class="card-title">
        Serviços do orçamento
    </div>

    <table>

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

    </table>

</div>


<div class="card total-card">

    <div class="total-label">
        VALOR TOTAL
    </div>

    <div class="total-value">
        R$ {{ number_format($total, 2, ',', '.') }}
    </div>

</div>


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