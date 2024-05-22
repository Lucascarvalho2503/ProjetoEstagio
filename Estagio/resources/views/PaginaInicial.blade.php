<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link rel="stylesheet" href="/css/stylePI.css">
</head>
<body>
    <div class="header">
        <h1>Bem vindo(a), -------</h1>
        <p>Londrina, Paraná Dia 13/03/2024</p>
    </div>
    <div class="container">
        <main>
            <h2>Vamos fazer o que hoje?</h2>
            <div class="menu">
                <button class="menu-item" onclick="location.href='{{ route('gerenciar-reserva') }}'">🛏️ Visualisar Quartos</button>
                <button class="menu-item">📦 Produtos</button>
                <button class="menu-item">📝 Comanda</button>
            </div>
        </main>
    </div>
</body>
</html>
