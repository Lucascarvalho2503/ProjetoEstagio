<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar Quarto</title>
    <link rel="stylesheet" href="/css/styleAER.css">
</head>
<body>
    <div class="main-content">
        <header>
            <h1>Reservar Quarto</h1>
        </header>
        <form action="{{ route('reservar-quarto', ['id' => $quarto->id]) }}" method="POST">
            @csrf
            <div>
                <label for="numero">Número do Quarto:</label>
                <input type="number" id="numero" name="numero" value="{{ $quarto->numero }}" readonly>
            </div>
            <div>
                <label for="hora_entrada">Hora de Entrada:</label>
                <input type="time" id="hora_entrada" name="hora_entrada" required>
            </div>
            <div>
                <label for="hora_saida">Hora Contratada:</label>
                <input type="time" id="hora_saida" name="hora_saida" required>
            </div>
            <div>
                <label for="nome_cliente">Nome do Cliente:</label>
                <input type="text" id="nome_cliente" name="nome_cliente" required>
            </div>
            <div>
                <label for="cpf">CPF:</label>
                <input type="text" id="cpf" name="cpf" required>
            </div>
            <button type="submit">Reservar</button>
        </form>
    </div>
</body>
</html>
