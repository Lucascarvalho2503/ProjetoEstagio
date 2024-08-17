<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comandas</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-200">

<div class="flex justify-center items-center flex-col mt-8">
    <!-- Cards de comandas -->
    <div class="grid grid-cols-1 mb-8 sm:grid-cols-2 lg:grid-cols-3 gap-4 justify-items-center mt-8">
        <div class="container mx-auto p-6">
            @if(isset($comandas)) <!-- Se $comandas estiver definido, então é a view de index -->
                @foreach($comandas as $comanda)
                    <div class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 mb-4">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Comanda de {{ $comanda->reserva->cliente->nome }}</h5>
                        <p class="font-normal text-gray-700 dark:text-gray-400">Quarto: {{ $comanda->reserva->quarto->tipo_de_quarto }}</p>

                        @if($comanda->status === 'open')
                            <form class="max-w-sm mt-2 mx-auto" method="POST" action="{{ route('comanda.adicionarProduto') }}">
                                @csrf
                                <input type="hidden" name="comanda_id" value="{{ $comanda->id }}">
                                <label for="product-select" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selecione o produto:</label>
                                <select id="product-select" name="produto_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                    @foreach($produtos as $produto)
                                        <option value="{{ $produto->id }}">{{ $produto->nome }} (Estoque: {{ $produto->estoque }})</option>
                                    @endforeach
                                </select>

                                <label for="number-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white mt-4">Quantidade:</label>
                                <input type="number" id="number-input" name="quantidade" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Quantidade" required min="1" max="100" />

                                <button type="submit" class="text-white h-10 mt-4 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Adicionar</button>
                            </form>
                        @else
                            <p class="font-normal text-gray-700 dark:text-gray-400 mt-4">Comanda finalizada. Não é possível adicionar produtos.</p>
                        @endif

                        <p class="font-normal text-gray-700 dark:text-gray-400 mt-4">Produtos:</p>
                        <ul>
                            @foreach($comanda->produtos as $produto)
                                <li class="text-white">
                                    {{ $produto->produto->nome }} - Quantidade: {{ $produto->quantidade }} - Valor: R$ {{ number_format($produto->produto->valor * $produto->quantidade, 2, ',', '.') }}
                                    @if($comanda->status === 'open')
                                    <form method="POST" action="{{ route('comanda.removerProduto', ['comanda' => $comanda->id, 'produto' => $produto->produto->id]) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 ml-2">X</button>
                                    </form>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        <p class="font-normal text-gray-700 dark:text-gray-400 mt-4">Valor da comanda: R$ {{ number_format($comanda->valor_comanda, 2, ',', '.') }}</p>
                    </div>
                @endforeach
            @elseif(isset($comanda)) <!-- Se $comanda estiver definido, então é a view de show -->
                <div class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 mb-4">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Comanda de {{ $comanda->reserva->cliente->nome }}</h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">Quarto: {{ $comanda->reserva->quarto->tipo_de_quarto }}</p>

                    @if($comanda->status === 'open')
                        <form class="max-w-sm mt-2 mx-auto" method="POST" action="{{ route('comanda.adicionarProduto') }}">
                            @csrf
                            <input type="hidden" name="comanda_id" value="{{ $comanda->id }}">
                            <label for="product-select" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selecione o produto:</label>
                            <select id="product-select" name="produto_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                @foreach($produtos as $produto)
                                    <option value="{{ $produto->id }}">{{ $produto->nome }} (Estoque: {{ $produto->estoque }})</option>
                                @endforeach
                            </select>

                            <label for="number-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white mt-4">Quantidade:</label>
                            <input type="number" id="number-input" name="quantidade" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Quantidade" required min="1" max="100" />

                            <button type="submit" class="text-white h-10 mt-4 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Adicionar</button>
                        </form>
                    @else
                        <p class="font-normal text-gray-700 dark:text-gray-400 mt-4">Comanda finalizada. Não é possível adicionar produtos.</p>
                    @endif

                    <p class="font-normal text-gray-700 dark:text-gray-400 mt-4">Produtos:</p>
                    <ul>
                        @foreach($comanda->produtos as $produto)
                            <li class="text-white">
                                {{ $produto->produto->nome }} - Quantidade: {{ $produto->quantidade }} - Valor: R$ {{ number_format($produto->produto->valor * $produto->quantidade, 2, ',', '.') }}
                                @if($comanda->status === 'open')
                                <form method="POST" action="{{ route('comanda.removerProduto', ['comanda' => $comanda->id, 'produto' => $produto->produto->id]) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 ml-2">X</button>
                                </form>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    <p class="font-normal text-gray-700 dark:text-gray-400 mt-4">Valor da comanda: R$ {{ number_format($comanda->valor_comanda, 2, ',', '.') }}</p>
                </div>
            @endif
        </div>
    </div>

</body>
</html>
