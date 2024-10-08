<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Quarto</title>
    <link rel="stylesheet" href="/css/styleAER.css">
</head>
<body>
<button class="back-button" onclick="history.back()">Voltar</button>

    <div class="main-content">
        <header>
            <h1>Editar Quarto</h1>
        </header>
        <form action="{{ route('atualizar-quarto', ['id' => $quartos->id]) }}" method="POST">
            @csrf
            @method('PUT')

            @if(request()->query('view') === 'CriarQuartos')
                <!-- Campos para CriarQuartos -->
                <div>
                    <label for="id">ID do Quarto:</label>
                    <input type="text" id="id" name="id" value="{{ $quartos->id }}" readonly>
                </div>
                <div>
                    <label for="numero">Número do Quarto:</label>
                    <input type="number" id="numero" name="numero" value="{{ $quartos->numero }}" required>
                </div>
                <div>
                    <label for="tamanho">Tamanho:</label>
                    <input type="text" id="tamanho" name="tamanho" value="{{ $quartos->tamanho }}" required>
                </div>
                <div>
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="ocupado" {{ $quartos->status == 'ocupado' ? 'selected' : '' }}>Ocupado</option>
                        <option value="disponível" {{ $quartos->status == 'disponível' ? 'selected' : '' }}>Disponível</option>
                        <option value="em manutenção" {{ $quartos->status == 'em manutenção' ? 'selected' : '' }}>Em Manutenção</option>
                        <option value="desativado" {{ $quartos->status == 'desativado' ? 'selected' : '' }}>Desativado</option>
                    </select>
                </div>
            @elseif(request()->query('view') === 'GerenciarReserva')
                <!-- Campos para GerenciarReserva -->
                <div>
                    <label for="hora_entrada">Hora de Entrada:</label>
                    <input type="time" id="hora_entrada" name="hora_entrada" value="{{ $quartos->hora_entrada }}" required>
                </div>
                <div>
                    <label for="hora_contratada">Hora Contratada:</label>
                    <input type="time" id="hora_contratada" name="hora_contratada" value="{{ $quartos->hora_contratada }}" required>
                </div>
                <div>
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="ocupado" {{ $quartos->status == 'ocupado' ? 'selected' : '' }}>Ocupado</option>
                        <option value="disponível" {{ $quartos->status == 'disponível' ? 'selected' : '' }}>Disponível</option>
                        <option value="em manutenção" {{ $quartos->status == 'em manutenção' ? 'selected' : '' }}>Em Manutenção</option>
                        <option value="desativado" {{ $quartos->status == 'desativado' ? 'selected' : '' }}>Desativado</option>
                    </select>
                </div>
            @endif

            <button type="submit">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>
