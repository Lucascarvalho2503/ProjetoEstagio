<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quartos</title>
    @vite('resources/css/app.css')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modal');
            const closeButton = document.getElementById('closeModal');
            const overlay = document.getElementById('overlay');
            const editButtons = document.querySelectorAll('.editButton');
            const tipoInput = document.getElementById('edit_tipo_de_quarto');
            const valorInput = document.getElementById('edit_valor_hora');
            let currentQuartoId;

            // Open modal when Edit is clicked
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const quarto = JSON.parse(this.getAttribute('data-quarto'));
                    currentQuartoId = quarto.id;
                    tipoInput.value = quarto.tipo_de_quarto;
                    valorInput.value = quarto.valor_hora;

                    // Set selected status in dropdown
                    document.querySelector(`#edit_status_${quarto.status.id}`).checked = true;

                    modal.classList.remove('hidden');
                    overlay.classList.remove('hidden');
                });
            });

            // Close modal when clicking outside or on the close button
            closeButton.addEventListener('click', closeModal);
            overlay.addEventListener('click', closeModal);

            function closeModal() {
                modal.classList.add('hidden');
                overlay.classList.add('hidden');
            }

            // Submit the form to update the quarto
            document.getElementById('editForm').addEventListener('submit', function(event) {
                event.preventDefault();
                const url = `/quartos/${currentQuartoId}`;
                const data = {
                    tipo_de_quarto: tipoInput.value,
                    valor_hora: valorInput.value,
                    status_id: document.querySelector('input[name="status_id"]:checked').value,
                    _token: document.querySelector('input[name="_token"]').value
                };

                fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data),
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    }
                });
            });
        });
    </script>
</head>
<body class="bg-gray-200">
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>

<x-sidebar></x-sidebar>

<div class="flex justify-center ml-64 items-center flex-col mt-8">
    <!-- Formulário para criar novos quartos -->

    <div class="w-[900px] mt-2 p-5 bg-gray-600 border border-black rounded-lg flex">
        <form action="{{ route('statuses.store') }}" method="POST" class="flex space-x-4 mt-1 w-full">
            @csrf
            <div class="w-1/2 mt-1">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status de quarto</label>
                <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Ex: Em manutenção" required />
            </div>
            <button type="submit" class="text-white ml-4 h-10 mt-8 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Criar</button>
        </form> 
    </div>

    <div class="w-[900px] mt-2 p-5 bg-gray-600 border border-black rounded-lg flex">
        <form action="{{ route('quartos.store') }}" method="POST" class="flex space-x-4 mt-1 w-full">
            @csrf

            @if ($errors->has('numero'))
            <div class="text-red-500 text-sm mb-4">
                {{ $errors->first('numero') }}
            </div>
            @endif

            
            <div class="w-1/2 mt-1">
                <label for="tipo_de_quarto" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de quarto</label>
                <input type="text" name="tipo_de_quarto" id="tipo_de_quarto" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Ex: Quarto de luxo" required />
            </div>
            <div class="w-1/2 mt-1">
                <label for="valor_hora" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Valor da hora</label>
                <input type="text" name="valor_hora" id="valor_hora" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>

            <div class="w-1/2 mt-1">
                <label for="numero" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Número do quarto</label>
                <input type="number" name="numero" id="numero" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>

            <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown" class="text-white h-10 mt-8 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">Status 
                <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                </svg>
            </button>

            <!-- Dropdown menu -->
            <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                    @foreach ($statuses as $status)
                    @if ($status->name !== 'Ocupado')
                    <li>
                        <div class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                            <input type="radio" id="status_{{ $status->id }}" name="status_id" value="{{ $status->id }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="status_{{ $status->id }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $status->name }}</label>
                        </div>
                    </li>
                    @endif
                    @endforeach
                </ul>
            </div>

            <button type="submit" class="text-white ml-4 h-10 mt-8 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Criar</button>
        </form>
    </div>

    <!-- Tabela para exibir os quartos -->
    <div class="relative mt-2 mb-4 w-[900px] h-full overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">ID do quarto</th>
                    <th scope="col" class="px-6 py-3">Número do quarto</th>
                    <th scope="col" class="px-6 py-3">Tipo do quarto</th>
                    <th scope="col" class="px-6 py-3">Valor da hora</th>
                    <th scope="col" class="px-6 py-3">Status do quarto</th>
                    <th scope="col" class="px-6 py-3">Configurar</th>
                    <th scope="col" class="px-6 py-3">Excluir</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quartos as $quarto)
                    <tr class="bg-white border-b dark:bg-gray-600 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $quarto->id }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $quarto->numero }}</td>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $quarto->tipo_de_quarto }}</th>
                        <td class="px-6 py-4">{{ $quarto->valor_hora }}</td>
                        <td class="px-6 py-4">{{ $quarto->status->name }}</td>
                        <td class="px-6 py-4">
                            <button class="text-white editButton bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                    data-quarto="{{ $quarto }}">
                                Editar
                            </button>
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('quartos.destroy', $quarto->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este quarto?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

<!-- Modal para editar quarto -->
<div id="modal" class="fixed inset-0 hidden z-50 overflow-y-auto">
    <div class="min-h-screen px-4 text-center">
        <div id="overlay" class="fixed inset-0 w-full h-full bg-black opacity-50"></div>
        <span class="inline-block h-screen align-middle" aria-hidden="true">&#8203;</span>
        <div class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-gray-600 shadow-xl rounded-lg">
            <div class="flex justify-between items-center pb-3">
                <h3 class="text-lg font-medium leading-6 text-gray-200">Editar Quarto</h3>
                <button id="closeModal" class="text-gray-400 hover:text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mt-2">
                    <label for="edit_tipo_de_quarto" class="block mb-2 text-sm font-medium text-gray-200 dark:text-white">Tipo de quarto</label>
                    <input type="text" id="edit_tipo_de_quarto" name="tipo_de_quarto" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                </div>
                <div class="mt-2">
                    <label for="edit_valor_hora" class="block mb-2 text-sm font-medium text-gray-200 dark:text-white">Valor da hora</label>
                    <input type="text" id="edit_valor_hora" name="valor_hora" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                </div>
                <div class="mt-2">
                    <label for="edit_numero" class="block mb-2 text-sm font-medium text-gray-200 dark:text-white">Número do quarto</label>
                    <input type="number" id="edit_numero" name="edit_numero" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                </div>
                <div class="mt-2">
                    <label class="block mb-2 text-sm font-medium text-gray-200 dark:text-white">Status</label>
                    @foreach ($statuses as $status)
                    @if ($status->name !== 'Ocupado')
                        <div class="flex items-center">
                            <input type="radio" id="edit_status_{{ $status->id }}" name="status_id" value="{{ $status->id }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="edit_status_{{ $status->id }}" class="ml-2 text-sm font-medium text-gray-200 dark:text-gray-300">{{ $status->name }}</label>
                        </div>
                    @endif
                    @endforeach
                </div>
                <div class="mt-4">
                    <button type="submit" class="w-full inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
