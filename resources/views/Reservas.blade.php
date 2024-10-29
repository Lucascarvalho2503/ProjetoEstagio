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

<x-sidebar></x-sidebar>

<!-- Modal de Reserva -->
<div id="reservationModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden z-50">
    <div class="bg-gray-600 border border-black rounded-lg w-96 p-6">
        <h2 class="text-xl font-bold text-white mb-4">Criar Reserva</h2>
        <form id="reservationForm" action="{{ route('reservas.store') }}" method="POST">
            @csrf
            <input type="hidden" id="modalQuartoId" name="quarto_id">
            <input type="hidden" id="clienteId" name="cliente_id"> <!-- Campo oculto para o cliente_id -->

            <!-- Campo de CPF para seleção do cliente -->
            <div class="relative inline-block text-left mb-4">
                <label for="cpf" class="block mb-2 text-sm font-medium text-gray-200">Digite o CPF do Cliente</label>
                <input type="text" id="cpfInput" class="text-sm px-5 py-2.5 w-full text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="CPF" required>
                <span id="cpfError" class="text-red-500 text-sm hidden">Usuário não encontrado!</span>
                <span id="cpfSuccess" class="text-green-500 text-sm hidden">Usuário encontrado: <span id="nomeCliente"></span></span>
            </div>

            <div class="mb-4">
                <label for="horario_entrada" class="block text-sm font-medium text-gray-200">Hora de Entrada</label>
                <input type="text" id="horario_entrada" name="horario_entrada" class="block w-full p-2.5 mt-1 text-sm text-gray-900 bg-gray-600 border border-gray-300 rounded-lg" placeholder="Ex: 14:00" required>
            </div>

            <div class="mb-4">
                <label for="horas_contratadas" class="block text-sm font-medium text-gray-200">Horas Contratadas</label>
                <input type="text" id="horas_contratadas" name="horas_contratadas" class="block w-full p-2.5 mt-1 text-sm text-gray-900 bg-gray-600 border border-gray-300 rounded-lg" min="1" required>
            </div>

            <button id="submitButton" type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5" disabled>
                Criar Reserva
            </button>

            <button type="button" onclick="hideReservationModal()" class="ml-2 text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5">Cancelar</button>
        </form>
    </div>
</div>


<div class="flex justify-center ml-64 items-center flex-row mt-6 gap-4">
<a href="{{ route('clientes.index') }}" class="text-white w-1/3 bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 inline-block text-center">
    Criar cliente
</a>
</div>

<div class="flex justify-center ml-64 items-center flex-col">
    <div class="w-10/11 mt-8 bg-gray-800 border border-black rounded-lg p-5">
        <h2 class="text-xl font-bold text-white mb-4">Todos os Quartos</h2>
        <table class="w-full text-sm text-gray-900">
            <thead class="text-xs text-gray-300 uppercase bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3">ID do Quarto</th>
                    <th scope="col" class="px-6 py-3">Número do Quarto</th>
                    <th scope="col" class="px-6 py-3">Tipo de Quarto</th>
                    <th scope="col" class="px-6 py-3">Valor por Hora</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quartos as $quarto)
                    <tr class="bg-gray-600 text-white text-center">
                        <td class="px-6 py-4">{{ $quarto->id }}</td>
                        <td class="px-6 py-4">{{ $quarto->numero }}</td>
                        <td class="px-6 py-4">{{ $quarto->tipo_de_quarto }}</td>
                        <td class="px-6 py-4">{{ $quarto->valor_hora }}</td>
                        <td class="px-6 py-4">{{ $quarto->status_id == 2 ? 'Ocupado' : 'Disponível' }}</td>
                        <td class="px-6 py-4">
                            @if ($quarto->currentReservation)
                                <button onclick="showVisualizarModal({{ $quarto->currentReservation->id }})" class="text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-4 py-2.5">Visualizar</button>
                                <button onclick="showEditModal({{ $quarto->currentReservation->id }})" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5">Editar</button>
                            
                                <form action="{{ route('reservas.destroy', $quarto->currentReservation->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Tem certeza que deseja excluir esta reserva?');" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                       <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                    </svg>
                                    </button>
                                </form>
                            @else
                                <button onclick="openReservationModal({{ $quarto->id }})" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5">Reservar</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<!-- Modal Clientes -->
<div id="clientesModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden">
    <div class="bg-gray-900 rounded-lg shadow-lg w-3/4 md:w-1/2 lg:w-1/3">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-xl text-white font-semibold">Clientes</h3>
            <button onclick="hideClientesModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-4">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nome</th>
                        <th scope="col" class="px-6 py-3">CPF</th>
                        <th scope="col" class="px-6 py-3">Contato</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientes as $cliente)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $cliente->nome }}</td>
                            <td class="px-6 py-4">{{ $cliente->cpf }}</td>
                            <td class="px-6 py-4">{{ $cliente->celular }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Modal de Edição -->
<div id="editModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden z-50">
    <div class="bg-gray-600 border border-black rounded-lg w-96 p-6">
        <h2 class="text-xl font-bold text-white mb-4">Editar Reserva</h2>
        <form id="editReservationForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_quarto_id" name="quarto_id">

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


<!-- Modal de Visualização -->
<div id="visualizarModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden z-50">
    <div class="bg-gray-600 border border-black rounded-lg w-1/2 p-6">
        <h2 class="text-xl font-bold text-white mb-4">Detalhes da Reserva</h2>
        <table class="w-full text-sm text-gray-900">
            <thead class="text-xs text-gray-300 uppercase bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3">Nome do Cliente</th>
                    <th scope="col" class="px-6 py-3">Valor por Hora</th>
                    <th scope="col" class="px-6 py-3">Horas Contratadas</th>
                    <th scope="col" class="px-6 py-3">Entrada-Saída</th>
                    <th scope="col" class="px-6 py-3">Valor Total</th>
                </tr>
            </thead>
            <tbody id="visualizarTableBody" class="bg-gray-800 text-center text-gray-300">
                <!-- Dados da reserva serão injetados aqui -->
            </tbody>
        </table>
        <button type="button" onclick="hideVisualizarModal()" class="mt-4 text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5">Fechar</button>
    </div>
</div>



<script>
    function openReservationModal(quartoId) {
        document.getElementById('reservationModal').classList.remove('hidden');
        document.getElementById('modalQuartoId').value = quartoId;
        document.getElementById('dropdownCliente').classList.add('hidden'); // Fecha o dropdown quando o modal é aberto
    }

    function hideReservationModal() {
        document.getElementById('reservationModal').classList.add('hidden');
    }

        function showEditModal(reservaId) {
        // Faz a requisição para buscar os dados da reserva
        fetch(`/reservas/${reservaId}/edit`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao buscar dados da reserva: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                // Preenche o formulário de edição com os dados recebidos
                document.getElementById('editReservationForm').action = `/reservas/${reservaId}`;
                document.getElementById('edit_quarto_id').value = data.quarto_id; 
                document.getElementById('edit_horario_entrada').value = data.horario_entrada; // Usa o horário de entrada no formato correto
                document.getElementById('edit_horas_contratadas').value = data.horas_contratadas;
                
                // Exibe o modal de edição
                document.getElementById('editModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Erro ao buscar dados da reserva:', error);
                alert('Não foi possível carregar os dados da reserva. Por favor, tente novamente.');
            });
    }

    function hideEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }


    function showVisualizarModal(reservaId) {
        fetch(`/reservas/${reservaId}/detalhes`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao buscar detalhes da reserva: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                const tbody = document.getElementById('visualizarTableBody');
                tbody.innerHTML = `
                    <tr>
                        <td class="px-6 py-4">${data.cliente.nome}</td>
                        <td class="px-6 py-4">${data.quarto.valor_hora}</td>
                        <td class="px-6 py-4">${data.horas_contratadas}</td>
                        <td class="px-6 py-4">${data.horario_entrada} - ${data.horario_saida || 'N/A'}</td>
                        <td class="px-6 py-4">${data.valor_total}</td>
                    </tr>
                `;
                document.getElementById('visualizarModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Erro ao buscar detalhes da reserva:', error);
                alert('Não foi possível carregar os detalhes da reserva. Por favor, tente novamente.');
            });
    }

    function hideVisualizarModal() {
        document.getElementById('visualizarModal').classList.add('hidden');
    }

    function finalizeReservation(reservaId) {
        if (confirm('Tem certeza que deseja finalizar esta reserva?')) {
            fetch(`/reservas/${reservaId}/finalizar`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
            .finally(() => {
                window.location.reload();
            });
        }
    }

    function showClientesModal() {
        document.getElementById('clientesModal').classList.remove('hidden');
    }

    function hideClientesModal() {
        document.getElementById('clientesModal').classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Dropdown do modal de reserva
        document.getElementById('dropdownClienteButton').addEventListener('click', () => {
            const dropdown = document.getElementById('dropdownCliente');
            dropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', (event) => {
            const dropdown = document.getElementById('dropdownCliente');
            const dropdownButton = document.getElementById('dropdownClienteButton');

            if (!dropdown.contains(event.target) && event.target !== dropdownButton) {
                dropdown.classList.add('hidden');
            }
        });

        // Dropdown do modal de edição
        document.getElementById('dropdownEditClienteButton').addEventListener('click', () => {
            const dropdown = document.getElementById('dropdownEditCliente');
            dropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', (event) => {
            const dropdown = document.getElementById('dropdownEditCliente');
            const dropdownButton = document.getElementById('dropdownEditClienteButton');

            if (!dropdown.contains(event.target) && event.target !== dropdownButton) {
                dropdown.classList.add('hidden');
            }
        });

        // Inicialização do Cleave.js para o campo de horário
        new Cleave('#horario_entrada', {
            time: true,
            timePattern: ['h', 'm']
        });

        // Inicialização do Cleave.js para o campo de horário no modal de edição
        new Cleave('#edit_horario_entrada', {
            time: true,
            timePattern: ['h', 'm']
        });
    });

    document.getElementById('cpfInput').addEventListener('blur', function () {
    let cpf = this.value.replace(/\D/g, ''); // Remove pontos e traços do CPF

    // Valida se o CPF tem 11 dígitos
    if (cpf.length !== 11) {
        document.getElementById('cpfError').classList.remove('hidden');
        document.getElementById('cpfSuccess').classList.add('hidden');
        document.getElementById('submitButton').disabled = true; // Desabilita o botão
        return;
    }

    // Limpa mensagens anteriores
    document.getElementById('cpfError').classList.add('hidden');
    document.getElementById('cpfSuccess').classList.add('hidden');

    // Faz a verificação do CPF no backend
    fetch('{{ route('reservations.verificarCpf') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ cpf: cpf })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Cliente encontrado, habilita o botão
            document.getElementById('cpfSuccess').classList.remove('hidden');
            document.getElementById('nomeCliente').textContent = data.cliente.nome;
            document.getElementById('clienteId').value = data.cliente.id; // Define o cliente_id
            document.getElementById('submitButton').disabled = false; // Habilita o botão
        } else {
            // Cliente não encontrado, exibe mensagem de erro e desabilita o botão
            document.getElementById('cpfError').classList.remove('hidden');
            document.getElementById('clienteId').value = ''; // Limpa o cliente_id
            document.getElementById('submitButton').disabled = true; // Desabilita o botão
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        document.getElementById('cpfError').classList.remove('hidden');
        document.getElementById('submitButton').disabled = true;
    });
});

    
</script>


</body>
</html>