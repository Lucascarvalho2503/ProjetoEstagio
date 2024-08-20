<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    @vite('resources/css/app.css')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modal');
            const closeButton = document.getElementById('closeModal');
            const overlay = document.getElementById('overlay');
            const editButtons = document.querySelectorAll('.editButton');
            const nomeInput = document.getElementById('edit_nome');
            const valorInput = document.getElementById('edit_valor');
            let currentProdutoId;

            // Abrir modal ao clicar em Editar
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const produto = JSON.parse(this.getAttribute('data-produto'));
                    currentProdutoId = produto.id;
                    nomeInput.value = produto.nome;
                    valorInput.value = produto.valor;

                    modal.classList.remove('hidden');
                    overlay.classList.remove('hidden');

                    // Define a URL de ação do formulário com o ID do produto
                    document.getElementById('editForm').action = `/produtos/${currentProdutoId}`;
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

                const form = this;
                const url = `/produtos/${currentProdutoId}`; // Certifique-se de que a URL contém o ID correto
                const formData = new FormData(form);

                // Obtém o token CSRF
                const csrfToken = document.querySelector('input[name="_token"]').value;

                fetch(url, {
                    method: 'POST',  // Corrige o método para 'POST' e usa o método 'PUT' no form
                    headers: {
                        'X-CSRF-TOKEN': csrfToken  // Inclui o token CSRF no cabeçalho
                    },
                    body: formData,
                })
                .then(response => {
                    if (response.ok) {
                        location.reload();  // Recarrega a página após a edição bem-sucedida
                    } else {
                        console.error('Erro ao atualizar o produto.');
                    }
                })
                .catch(error => console.error('Erro:', error));
            });
        });
    </script>
</head>
<body class="bg-gray-200">

<x-sidebar></x-sidebar>


<div class="flex justify-center ml-64 items-center flex-col mt-8">
    <!-- Formulário para criar novos produtos -->
    <div class="w-[580px] mt-2 p-5 bg-gray-600 border border-black rounded-lg">
        <form action="{{ route('produtos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="w-full">
                <label for="nome" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome do produto</label>
                <input type="text" name="nome" id="nome" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>
            <div class="w-full mt-2">
                <label for="valor" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Valor do produto</label>
                <input type="text" name="valor" id="valor" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>
            <div class="w-full mt-2">
                <label for="estoque" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Estoque do produto</label>
                <input type="text" name="estoque" id="estoque" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>
            <label class="block mb-2 mt-2 text-sm font-medium text-gray-900 dark:text-white" for="imagem">Foto do produto (.webp)</label>
            <input class="block w-full mb-5 text-xs text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="imagem" name="imagem" type="file">
            <button type="submit" class="text-white h-10 mt-2 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Criar produto</button>
        </form>
    </div>

    <!-- Cards de produtos -->
    <div class="grid grid-cols-1 mb-8 sm:grid-cols-2 lg:grid-cols-3 gap-4 justify-items-center mt-8">
        @foreach ($produtos as $produto)
            <div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <img class="rounded-t-lg" src="{{ $produto->imagem ? Storage::url($produto->imagem) : '/default-image.jpg' }}" alt="Imagem do produto" />
                <div class="p-5">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $produto->nome }}</h5>
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">R$ {{ number_format($produto->valor, 2, ',', '.') }}</p>
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Quantidade: {{ $produto->estoque }}</p>
                    <div class="flex">
                        <button data-produto="{{ json_encode($produto) }}" class="editButton inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800">
                            Editar
                            <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                            </svg>
                        </button>

                        <form action="{{ route('produtos.destroy', $produto->id) }}" method="POST" class="ml-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300">
                                Excluir
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal de edição -->
<div id="modal" class="fixed inset-0 flex items-center justify-center hidden z-50">
    <div class="bg-gray-600 p-5 rounded-lg shadow-lg relative w-[580px]">
        <button id="closeModal" class="absolute top-2 right-2 text-white">&times;</button>
        <form id="editForm" action="" method="POST" enctype="multipart/form-data" class="flex flex-col space-y-4">
            @csrf
            @method('PUT')
            <div class="w-full">
                <label for="edit_nome" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Editar nome do produto</label>
                <input type="text" name="nome" id="edit_nome" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
            </div>
            <div class="w-full">
                <label for="edit_valor" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Editar valor do produto</label>
                <input type="text" name="valor" id="edit_valor" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
            </div>
            <div class="w-full">
                <label for="estoque" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Editar valor do produto</label>
                <input type="text" name="estoque" id="estoque" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
            </div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="edit_imagem">Editar foto do produto (.webp)</label>
            <input class="block w-full mb-5 text-xs text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="edit_imagem" name="imagem" type="file">
            <button type="submit" class="text-white h-10 mt-2 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5">Salvar</button>
        </form>
    </div>
</div>

<!-- Overlay -->
<div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40"></div>

</body>
</html>
