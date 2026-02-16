<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans; }
        h1 { text-align: center; }
        .card {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <h1>Relatorio para aprovar</h1>

    <p><?= $id ?></p>
    <p><?= $customer['name']; ?></p>
    <p><?= $vehicle['model']; ?></p>
    <p><?= $license_plate ?></p>
    <p><?= $notes ?></p>

    <h1>Serviços do orçamento</h1>

    <p><?= $services['service_name']; ?></p>
    <p><?= $services['unit_price']; ?></p>
    
    
    
</body>
</html>
