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
                <li><a href="{{ route('inicio') }}">Página Inicial</a></li>
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
            <button class="add-room" onclick="window.location.href='{{ route('adicionar-quarto-form') }}'">Adicionar Quarto</button>
        </header>
        <table>
            <thead>
                <tr>
                    <th>id Quarto</th>
                    <th>Quarto</th>
                    <th>Hr. Entrada</th>
                    <th>Hr. Contratada</th>
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
                    <td>{{ $quarto->hora_contratada }}</td>
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
                        <button class="edit" onclick="window.location.href='{{ route('editar-quarto', ['id' => $quarto->id]) }}'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                </svg>
                        </button>
                        <button class="view" onclick="window.location.href='{{ route('visualizar-quarto', ['id' => $quarto->id]) }}'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                        </svg>
                        </button>
                        <form action="{{ route('excluir-quarto', ['id' => $quarto->id]) }}" method="POST" class="inline-form">
                            @csrf
                            @method('DELETE')
                        <button class="delete" type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                        </svg>
                        </button>
                        </form>
                        @if ($quarto->status == 'disponivel')
                        <button class="reservar" onclick="window.location.href='{{ route('reservar-quarto-form', ['id' => $quarto->id]) }}'">Reservar</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>
</body>
</html>
