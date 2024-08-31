<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comandas</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-200">

<x-sidebar></x-sidebar>

<div class="flex justify-center items-center flex-col w-full max-w-3xl mx-auto">
    <div class="relative w-full ml-64">
        <input type="text" id="search-input" class="block w-full mt-8 rounded-t-lg px-2.5 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 dark:bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
        <label for="search-input" class="absolute text-center mt-1 text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-1/2 left-0 right-0 text-center origin-[0] peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4">Buscar Comanda por nome de cliente</label>
    </div>
</div>

<h1 class="text-2xl ml-64 font-bold mt-4 text-center">Comandas Abertas</h1>

<div class="flex justify-center ml-64 items-center mt-2 flex-col mt-8">
    <!-- Cards de comandas abertas -->
    <div id="open-comandas" class="grid grid-cols-3 gap-4 justify-items-center mt-8">
        @foreach($comandas as $comanda)
            @if($comanda->status === 'open')
                <div class="comanda-card block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 mb-4">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Comanda de {{ $comanda->reserva->cliente->nome }}</h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">Quarto: {{ $comanda->reserva->quarto->tipo_de_quarto }}</p>

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

                    <p class="font-normal text-gray-700 dark:text-gray-400 mt-4">Produtos:</p>
                    <ul>
                        @foreach($comanda->produtos as $produto)
                            <li class="text-white">
                                {{ $produto->produto->nome }} - Quantidade: {{ $produto->quantidade }} - Valor: R$ {{ number_format($produto->produto->valor * $produto->quantidade, 2, ',', ',') }}
                                <form method="POST" action="{{ route('comanda.removerProduto', ['comanda' => $comanda->id, 'produto' => $produto->produto->id]) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 ml-2">X</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                    <p class="font-normal text-gray-700 dark:text-gray-400 mt-4">Horário de entrada: {{ \Carbon\Carbon::parse($comanda->reserva->horario_entrada)->format('H:i') }} </p>
                    <p class="font-normal text-gray-700 dark:text-gray-400 mt-4">Horas contratadas: {{ $comanda->reserva->horas_contratadas }} </p>
                    <p class="font-normal text-gray-700 dark:text-gray-400 mt-4">Valor da comanda: R$ {{ number_format($comanda->valor_comanda, 2, ',', ',') }}</p>
                </div>
            @endif
        @endforeach
    </div>
</div>

<h1 class="text-2xl font-bold ml-64 mt-4 text-center">Comandas Fechadas</h1>
<div class="flex ml-64 justify-center mt-2 mb-4 overflow-x-auto ">
    <table id="closed-comandas" class="w-10/12 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">Nome na comanda</th>
                <th scope="col" class="px-6 py-3">Quarto</th>
                <th scope="col" class="px-6 py-3">Produtos</th>
                <th scope="col" class="px-6 py-3">Valor total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comandas as $comanda)
                @if($comanda->status === 'closed')
                    <tr class="comanda-row bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $comanda->reserva->cliente->nome }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $comanda->reserva->quarto->tipo_de_quarto }}
                        </td>
                        <td class="px-6 py-4">
                            @foreach($comanda->produtos as $produto)
                                {{ $produto->quantidade }} - {{ $produto->produto->nome }} - R$ {{ number_format($produto->produto->valor * $produto->quantidade, 2, ',', ',') }}<br>
                            @endforeach
                        </td>
                        <td class="px-6 py-4">
                            R$ {{ number_format($comanda->valor_comanda, 2, ',', ',') }}
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>

<script>
    document.getElementById('search-input').addEventListener('input', function () {
        const searchValue = this.value.toLowerCase();

        // Filtrar comandas abertas
        document.querySelectorAll('.comanda-card').forEach(card => {
            const clientName = card.querySelector('h5').innerText.toLowerCase();
            card.style.display = clientName.includes(searchValue) ? 'block' : 'none';
        });

        // Filtrar comandas fechadas
        document.querySelectorAll('.comanda-row').forEach(row => {
            const clientName = row.querySelector('th').innerText.toLowerCase();
            row.style.display = clientName.includes(searchValue) ? 'table-row' : 'none';
        });
    });
</script>

</body>
</html>