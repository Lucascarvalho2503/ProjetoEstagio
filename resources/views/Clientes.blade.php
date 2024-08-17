<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    @vite('resources/css/app.css')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modal');
            const closeButton = document.getElementById('closeModal');
            const overlay = document.getElementById('overlay');
            const editButtons = document.querySelectorAll('.editButton');
            const nomeInput = document.getElementById('edit_nome');
            const cpfInput = document.getElementById('edit_cpf');
            const celularInput = document.getElementById('edit_celular');
            let currentClienteId;

            // Abrir modal ao clicar em Editar
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const cliente = JSON.parse(this.getAttribute('data-cliente'));
                    currentClienteId = cliente.id;
                    nomeInput.value = cliente.nome;
                    cpfInput.value = cliente.cpf;
                    celularInput.value = cliente.celular;

                    modal.classList.remove('hidden');
                    overlay.classList.remove('hidden');
                });
            });

            // Fechar modal ao clicar no X ou fora do modal
            closeButton.addEventListener('click', closeModal);
            overlay.addEventListener('click', closeModal);

            function closeModal() {
                modal.classList.add('hidden');
                overlay.classList.add('hidden');
            }

            // Submeter o formulário de edição
            document.getElementById('editForm').addEventListener('submit', function(event) {
                event.preventDefault();
                const url = `/clientes/${currentClienteId}`;
                const data = {
                    nome: nomeInput.value,
                    cpf: cpfInput.value,
                    celular: celularInput.value,
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

<div class="flex justify-center items-center flex-col mt-8">
    <!-- Formulário para criar novos clientes -->
    <div class="w-[700px] p-5 bg-gray-600 border border-black rounded-lg">
        <form action="{{ route('clientes.store') }}" method="POST" class="mt-1">
            @csrf
            <div class="w-full">
                <label for="nome" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome do cliente</label>
                <input type="text" name="nome" id="nome" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>
            <div class="w-full mt-2">
                <label for="cpf" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">CPF do cliente</label>
                <input type="text" name="cpf" id="cpf" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>
            <div class="w-full mt-2">
                <label for="celular" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Celular do cliente</label>
                <input type="text" name="celular" id="celular" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>
            <button type="submit" class="text-white h-10 mt-2 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Criar cliente</button>
        </form>
    </div>

    <!-- Tabela para exibir os clientes -->
  <div class="relative mt-2 w-[700px] h-full overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">Nome do cliente</th>
                <th scope="col" class="px-6 py-3">CPF</th>
                <th scope="col" class="px-6 py-3">Número de celular</th>
                <th scope="col" class="px-6 py-3">Editar</th>
                <th scope="col" class="px-6 py-3">Excluir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clientes as $cliente)
                <tr class="bg-white border-b dark:bg-gray-600 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $cliente->nome }}
                    </th>
                    <td class="px-6 py-4 text-gray-300">
                        {{ $cliente->cpf }}
                    </td>
                    <td class="px-6 py-4 text-gray-300">
                        {{ $cliente->celular }}
                    </td>
                    <td class="px-6 py-4 text-blue-600 editButton" data-cliente="{{ json_encode($cliente) }}">
                        Editar
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este cliente?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white p-2 rounded-full hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
  </div>
</div>

<!-- Modal para editar cliente -->
<div id="modal" class="fixed inset-0 flex items-center justify-center hidden z-50">
    <div class="bg-gray-600 p-5 rounded-lg shadow-lg relative w-[700px]">
        <button id="closeModal" class="absolute top-2 right-2 text-white">&times;</button>
        <form id="editForm" action="" method="POST" class="flex flex-col space-y-4">
            @csrf
            @method('PUT')
            <div class="w-full">
                <label for="edit_nome" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Editar nome do cliente</label>
                <input type="text" name="edit_nome" id="edit_nome" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>
            <div class="w-full">
                <label for="edit_cpf" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Editar CPF do cliente</label>
                <input type="text" name="edit_cpf" id="edit_cpf" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>
            <div class="w-full">
                <label for="edit_celular" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Editar celular do cliente</label>
                <input type="text" name="edit_celular" id="edit_celular" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>
            <button type="submit" class="text-white h-10 mt-2 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Salvar</button>
        </form>
    </div>
</div>

<!-- Overlay -->
<div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40"></div>

</body>
</html>
