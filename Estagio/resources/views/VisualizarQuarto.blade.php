<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Quarto</title>
    <link rel="stylesheet" href="/css/styleAER.css">
</head>
<body>
<button class="back-button" onclick="history.back()">Voltar</button>

    <div class="main-content">
        <header>
            <h1>Visualizar Quarto</h1>
        </header>
        <div class="quarto-details">
            <div class="detail">
                <label>Número do Quarto:</label>
                <span>{{ $quarto->numero }}</span>
            </div>
            <div class="detail">
                <label>Hora de Entrada:</label>
                <span>{{ $quarto->hora_entrada }}</span>
            </div>
            <div class="detail">
                <label>Hora Contratada:</label>
                <span>{{ $quarto->hora_contratada }}</span>
            </div>
            <div class="detail">
                <label>Nome do Cliente:</label>
                <span>{{ $quarto->cliente->nome ?? 'N/A' }}</span>
            </div>
            <div class="detail">
                <label>CPF:</label>
                <span>{{ $quarto->cliente->cpf ?? 'N/A' }}</span>
            </div>
        </div>
    </div>
</body>
</html>
