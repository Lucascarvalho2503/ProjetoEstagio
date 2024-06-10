<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Quarto</title>
    <link rel="stylesheet" href="/css/styleAER.css">
</head>
<body>
    <button class="back-button" onclick="window.location.href='{{ route('gerenciar-reserva') }}'">Voltar</button>
    <div class="main-content">
        <header>
            
            <h1>Adicionar Quarto</h1>
        </header>
        <form action="{{ route('adicionar-quarto') }}" method="POST">
            @csrf
            <div>
                <label for="numero">Número do Quarto:</label>
                <input type="number" id="numero" name="numero" required>
            </div>
            <div>
                <label for="hora_entrada">Hora de Entrada:</label>
                <input type="time" id="hora_entrada" name="hora_entrada" required>
            </div>
            <div>
                <label for="hora_contratada">Hora Contratada:</label>
                <input type="time" id="hora_contratada" name="hora_contratada" required>
            </div>
            <div>
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="ocupado">Ocupado</option>
                    <option value="disponível">Disponível</option>
                    <option value="em manutenção">Em Manutenção</option>
                    <option value="desativado">Desativado</option>
                </select>
            </div>
            <button type="submit">Adicionar Quarto</button>
        </form>
    </div>
</body>
</html>
