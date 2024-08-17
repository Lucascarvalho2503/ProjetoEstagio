<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
</head>
<body class="bg-gray-200">
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>

<div class="flex justify-center items-center flex-col mt-8">
    <!-- Formulário para criar novas reservas -->
    <div class="w-11/12 p-5 bg-gray-800 border border-black rounded-lg">
        <form id="reservationForm" action="{{ route('reservas.store') }}" method="POST" class="mt-1">
            @csrf

            <!-- Dropdown para seleção do quarto -->
<div class="relative inline-block text-left mb-4">
    <button id="dropdownQuartoButton" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center">
        Quartos <svg class="w-2.5 h-2.5 ml-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/></svg>
    </button>
    <div id="dropdownQuarto" class="absolute top-full left-0 z-50 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
        @php
            // Filtra os quartos com status 'Disponível' (supondo que o valor é 1)
            $quartosDisponiveis = $quartos->filter(function ($quarto) {
                return $quarto->status_id == 1; // 1 é o valor para 'Disponível'
            });
        @endphp
        <ul class="py-2 text-sm text-gray-700">
            @foreach ($quartosDisponiveis as $quarto)
                <li>
                    <div class="flex items-center px-4 py-2 hover:bg-gray-100">
                        <input type="radio" id="quarto_{{ $quarto->id }}" name="quarto_id" value="{{ $quarto->id }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                        <label for="quarto_{{ $quarto->id }}" class="ml-2 text-sm font-medium">{{ $quarto->id }} - {{ $quarto->tipo_de_quarto }}</label>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>


            <!-- Dropdown para seleção do cliente -->
            <div class="relative inline-block text-left mb-4">
                <button id="dropdownClienteButton" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center">
                    Clientes <svg class="w-2.5 h-2.5 ml-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/></svg>
                </button>
                <div id="dropdownCliente" class="absolute top-full left-0 z-50 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                    <ul class="py-2 text-sm text-gray-700">
                        @foreach ($clientes as $cliente)
                        <li>
                            <div class="flex items-center px-4 py-2 hover:bg-gray-100">
                                <input type="radio" id="cliente_{{ $cliente->id }}" name="cliente_id" value="{{ $cliente->id }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                <label for="cliente_{{ $cliente->id }}" class="ml-2 text-sm font-medium">{{ $cliente->nome }}</label>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="mb-4">
                <label for="horario_entrada" class="block text-sm font-medium text-gray-200">Hora de Entrada</label>
                <input type="text" id="horario_entrada" name="horario_entrada" class="block w-full p-2.5 mt-1 text-sm text-gray-900 bg-gray-600 border border-gray-300 rounded-lg" placeholder="Ex: 14:00" required>
            </div>

            <div class="mb-4">
                <label for="horas_contratadas" class="block text-sm font-medium text-gray-200">Horas Contratadas</label>
                <input type="number" id="horas_contratadas" name="horas_contratadas" class="block w-full p-2.5 mt-1 text-sm text-gray-900 bg-gray-600 border border-gray-300 rounded-lg" min="1" required>
            </div>

            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Criar Reserva</button>
        </form>
    </div>

    <!-- Tabela de reservas -->
    <div class="w-11/12 mt-8 bg-gray-800 border border-black rounded-lg p-5">
    <h2 class="text-xl font-bold text-white mb-4">Reservas Ativas</h2>
    <table class="w-full text-sm text-gray-900">
        <thead class="text-xs text-gray-300 uppercase bg-gray-700">
            <tr>
                <th scope="col" class="px-6 py-3">Status do Quarto</th>
                <th scope="col" class="px-6 py-3">Nome do Quarto</th>
                <th scope="col" class="px-6 py-3">Valor da Reserva</th>
                <th scope="col" class="px-6 py-3">Horário de estadia</th>
                <th scope="col" class="px-6 py-3">Nome do Cliente</th>
                <th scope="col" class="px-6 py-3">Número do Cliente</th>
                <th scope="col" class="px-6 py-3">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservations->filter(fn($reservation) => $reservation->status === 'open') as $reservation)
            <tr class="bg-gray-600 text-white text-center">
                <td class="px-6 py-4">{{ $reservation->quarto->status_id == 2 ? 'Ocupado' : 'Disponível' }}</td>
                <td class="px-6 py-4">{{ $reservation->quarto->id }} - {{ $reservation->quarto->tipo_de_quarto }}</td>
                <td class="px-6 py-4">{{ $reservation->quarto->valor_hora * $reservation->horas_contratadas }}</td>
                <td class="px-6 py-4">
                @php
                    $horarioEntrada = \Carbon\Carbon::parse($reservation->horario_entrada);
                    $horarioSaida = $horarioEntrada->copy()->addHours($reservation->horas_contratadas);
                @endphp
                {{ $horarioEntrada->format('H:i') }} - {{ $horarioSaida->format('H:i') }}
                </td>
                <td class="px-6 py-4">{{ $reservation->cliente->nome }}</td>
                <td class="px-6 py-4">{{ $reservation->cliente->celular }}</td>
                <td class="px-6 py-4">
                    <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5" onclick="showEditModal({{ $reservation->id }})">Editar</button>
                    <form action="{{ route('reservas.finalizar', $reservation->id) }}" method="POST" class="inline-block ml-2">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2.5">Finalizar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="w-11/12 mt-8 bg-gray-800 border border-black rounded-lg p-5">
    <h2 class="text-xl font-bold text-white mb-4">Reservas finalizadas</h2>
    <table class="w-full text-sm text-gray-900">
        <thead class="text-xs text-gray-300 uppercase bg-gray-700">
            <tr>
                <th scope="col" class="px-6 py-3">Status do Quarto</th>
                <th scope="col" class="px-6 py-3">Nome do Quarto</th>
                <th scope="col" class="px-6 py-3">Valor da Reserva</th>
                <th scope="col" class="px-6 py-3">Horário de estadia</th>
                <th scope="col" class="px-6 py-3">Nome do Cliente</th>
                <th scope="col" class="px-6 py-3">Número do Cliente</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservations->filter(fn($reservation) => $reservation->status === 'close') as $reservation)
            <tr class="bg-gray-600 text-white text-center">
                <td class="px-6 py-4">{{ $reservation->quarto->status_id == 2 ? 'Ocupado' : 'Disponível' }}</td>
                <td class="px-6 py-4">{{ $reservation->quarto->id }} - {{ $reservation->quarto->tipo_de_quarto }}</td>
                <td class="px-6 py-4">{{ $reservation->quarto->valor_hora * $reservation->horas_contratadas }}</td>
                <td class="px-6 py-4">
                @php
                    $horarioEntrada = \Carbon\Carbon::parse($reservation->horario_entrada);
                    $horarioSaida = $horarioEntrada->copy()->addHours($reservation->horas_contratadas);
                @endphp
                {{ $horarioEntrada->format('H:i') }} - {{ $horarioSaida->format('H:i') }}
                </td>
                <td class="px-6 py-4">{{ $reservation->cliente->nome }}</td>
                <td class="px-6 py-4">{{ $reservation->cliente->celular }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


</div>



<!-- Modal de Edição -->
<div id="editModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden z-50">
    <div class="bg-gray-600 border border-black rounded-lg w-96 p-6">
        <h2 class="text-xl font-bold text-white mb-4">Editar Reserva</h2>
        <form id="editReservationForm" method="POST">
            @csrf
            @method('PUT')

            <!-- Dropdown para edição de seleção do quarto -->
<div class="relative inline-block text-left mb-4">
    <button id="dropdownEditQuartoButton" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center">
        Quartos <svg class="w-2.5 h-2.5 ml-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/></svg>
    </button>
    <div id="dropdownEditQuarto" class="absolute top-full left-0 z-50 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
        @php
            // Filtra os quartos com status 'Disponível' (supondo que o valor é 1)
            $quartosDisponiveis = $quartos->filter(function ($quarto) {
                return $quarto->status_id == 1; // 1 é o valor para 'Disponível'
            });

            // Obtém os IDs dos quartos ocupados
            $ocupados = $reservations->where('status', 'open')->pluck('quarto_id')->toArray();
        @endphp

        <ul class="py-2 text-sm text-gray-700">
            @foreach ($quartosDisponiveis as $quarto)
                @if (!in_array($quarto->id, $ocupados))
                    <li>
                        <div class="flex items-center px-4 py-2 hover:bg-gray-100">
                            <input type="radio" id="quarto_{{ $quarto->id }}" name="quarto_id" value="{{ $quarto->id }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                            <label for="quarto_{{ $quarto->id }}" class="ml-2 text-sm font-medium">{{ $quarto->id }} - {{ $quarto->tipo_de_quarto }}</label>
                        </div>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>


            <!-- Dropdown para edição de seleção do cliente -->
            <div class="relative inline-block text-left mb-4">
                <button id="dropdownEditClienteButton" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center">
                    Clientes <svg class="w-2.5 h-2.5 ml-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/></svg>
                </button>
                <div id="dropdownEditCliente" class="absolute top-full left-0 z-50 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                    <ul class="py-2 text-sm text-gray-700">
                        @foreach ($clientes as $cliente)
                        <li>
                            <div class="flex items-center px-4 py-2 hover:bg-gray-100">
                                <input type="radio" id="edit_cliente_{{ $cliente->id }}" name="cliente_id" value="{{ $cliente->id }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                <label for="edit_cliente_{{ $cliente->id }}" class="ml-2 text-sm font-medium">{{ $cliente->nome }}</label>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="mb-4">
                <label for="edit_horario_entrada" class="block text-sm font-medium text-white">Hora de Entrada</label>
                <input type="text" id="edit_horario_entrada" name="horario_entrada" class="block w-full p-2.5 mt-1 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg" placeholder="Ex: 14:00" required>
            </div>

            <div class="mb-4">
                <label for="edit_horas_contratadas" class="block text-sm font-medium text-white">Horas Contratadas</label>
                <input type="number" id="edit_horas_contratadas" name="horas_contratadas" class="block w-full p-2.5 mt-1 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg" min="1" required>
            </div>

            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Salvar Alterações</button>
            <button type="button" onclick="hideEditModal()" class="ml-2 text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5">Cancelar</button>
        </form>
    </div>
</div>

<script>
    // Função para mostrar o modal de edição
    function showEditModal(reservationId) {
        var form = document.getElementById('editReservationForm');
        form.action = '/reservas/' + reservationId;
        document.getElementById('editModal').classList.remove('hidden');
    }

    // Função para esconder o modal de edição
    function hideEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // Configuração do Cleave.js para formatar a hora
    new Cleave('#horario_entrada', {
        time: true,
        timePattern: ['h', 'm']
    });
    new Cleave('#edit_horario_entrada', {
        time: true,
        timePattern: ['h', 'm']
    });

    // Dropdown Quarto
    document.getElementById('dropdownQuartoButton').addEventListener('click', function () {
        document.getElementById('dropdownQuarto').classList.toggle('hidden');
    });

    document.getElementById('dropdownClienteButton').addEventListener('click', function () {
        document.getElementById('dropdownCliente').classList.toggle('hidden');
    });

    document.getElementById('dropdownEditQuartoButton').addEventListener('click', function () {
        document.getElementById('dropdownEditQuarto').classList.toggle('hidden');
    });

    document.getElementById('dropdownEditClienteButton').addEventListener('click', function () {
        document.getElementById('dropdownEditCliente').classList.toggle('hidden');
    });
</script>
</body>
</html>
