<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Quartos</title>
    <link rel="stylesheet" href="/css/styleGerenciar.css">
</head>
<body>
    <div class="sidebar">
        <div class="user-section">
            <span>Usuário</span>
        </div>
        <nav>
            <ul>
                <li><a href="#">Página Inicial</a></li>
                <li><a href="#" class="active">Visualizar Quartos</a></li>
                <li><a href="#">Produtos</a></li>
                <li><a href="#">Comanda</a></li>
            </ul>
        </nav>
    </div>
    <div class="main-content">
        <header>
            <h1>Visualizar Quartos</h1>
            <div class="search-bar">
                <input type="text" placeholder="Pesquisar por quarto">
                <button>↕ Ocupados</button>
            </div>
        </header>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <table>
            <thead>
                <tr>
                    <th>id Quarto</th>
                    <th>Quarto</th>
                    <th>Hr. Entrada</th>
                    <th>Hr. Saída</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quartos as $quarto)
                <tr>
                    <td>{{ $quarto->id }}</td>
                    <td>{{ $quarto->numero }}</td>
                    <td>{{ $quarto->hora_entrada }}</td>
                    <td>{{ $quarto->hora_saida }}</td>
                    <td><span class="status 
                    {{ $quarto->status == 'ocupado' ? 'ocupado' : '' }}
                    {{ $quarto->status == 'disponivel' ? 'disponivel' : '' }} 
                    {{ $quarto->status == 'em manutencao' ? 'manutencao' : '' }} 
                    {{ $quarto->status == 'desativado' ? 'desativado' : '' }}">
                    {{ $quarto->status }}
                    </span>
                    </td>
                    <td>
                        <button class="edit" onclick="window.location.href='{{ route('editar-quarto', ['id' => $quarto->id]) }}'">Editar</button>
                        <button class="view">Visualizar</button>
                        <form action="{{ route('excluir-quarto', ['id' => $quarto->id]) }}" method="POST" class="inline-form">
                            @csrf
                            @method('DELETE')
                            <button class="delete" type="submit">Excluir</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <button class="add-room" onclick="window.location.href='{{ route('adicionar-quarto-form') }}'">Adicionar Quarto</button>
    </div>
</body>
</html>