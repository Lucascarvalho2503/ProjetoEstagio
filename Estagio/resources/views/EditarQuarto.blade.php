<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar</title>
    <link rel="stylesheet" href="/css/styleGerenciar.css">
</head>
<body>
    <div class="main-content">
        <header>
            <h1>Editar quarto</h1>
        </header>
        <form action="{{ route('atualizar-quarto', ['id' => $quartos->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div>
                <label for="numero">Número do Quarto:</label>
                <input type="number" id="numero" name="numero" value="{{ $quartos->numero }}" required>
            </div>
            <div>
                <label for="hora_entrada">Hora de Entrada:</label>
                <input type="time" id="hora_entrada" name="hora_entrada" value="{{ $quartos->hora_entrada }}" required>
            </div>
            <div>
                <label for="hora_saida">Hora de Saída:</label>
                <input type="time" id="hora_saida" name="hora_saida" value="{{ $quartos->hora_saida }}" required>
            </div>
            <div>
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="ocupado" {{ $quartos->status == 'ocupado' ? 'selected' : '' }}>ocupado</option>
                    <option value="disponivel" {{ $quartos->status == 'disponivel' ? 'selected' : '' }}>disponivel</option>
                    <option value="em manutencao" {{ $quartos->status == 'em manutencao' ? 'selected' : '' }}>em manutencao</option>
                    <option value="desativado" {{ $quartos->status == 'desativado' ? 'selected' : '' }}>desativado</option>
                </select>

            </div>
            <button type="submit">Editar Quarto</button>
        </form>
    </div>
</body>
</html>
