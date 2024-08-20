<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
</head>
<body>


<div class="flex justify-center items-center h-screen space-x-4">

  <a href="{{ route('quartos.index') }}" class="block w-48 h-48 p-6 bg-white border border-gray-200 rounded-3xl shadow hover:bg-gray-100 dark:bg-gray-600 dark:border-gray-700 dark:hover:bg-gray-700">
    <h5 class="mb-2 text-xl font-bold text-center tracking-tight text-gray-900 dark:text-white">Quartos</h5>
    <div class="flex justify-center mt-4">
      <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-door-closed-fill text-white" viewBox="0 0 16 16">
        <path d="M12 1a1 1 0 0 1 1 1v13h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V2a1 1 0 0 1 1-1zm-2 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
      </svg>
    </div>
  </a>


  <a href="{{ route('clientes.index') }}" class="block w-48 h-48 p-6 bg-white border border-gray-200 rounded-3xl shadow hover:bg-gray-100 dark:bg-gray-600 dark:border-gray-700 dark:hover:bg-gray-700">
    <h5 class="mb-2 text-xl font-bold text-center tracking-tight text-gray-900 dark:text-white">Clientes</h5>
    <div class="flex justify-center mt-4">
      <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-person-fill-check text-white" viewBox="0 0 16 16">
        <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
        <path d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4"/>
      </svg>
    </div>
  </a>


  <a href="{{ route('reservas.index') }}" class="block w-48 h-48 p-6 bg-white border border-gray-200 rounded-3xl shadow hover:bg-gray-100 dark:bg-gray-600 dark:border-gray-700 dark:hover:bg-gray-700">
    <h5 class="mb-2 text-xl font-bold text-center tracking-tight text-gray-900 dark:text-white">Reservas</h5>
    <div class="flex justify-center mt-4">
      <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-border-all text-white" viewBox="0 0 16 16">
        <path d="M0 0h16v16H0zm1 1v6.5h6.5V1zm7.5 0v6.5H15V1zM15 8.5H8.5V15H15zM7.5 15V8.5H1V15z"/>
      </svg>
    </div>
  </a>


  <a href="{{ route('produtos.index') }}" class="block w-48 h-48 p-6 bg-white border border-gray-200 rounded-3xl shadow hover:bg-gray-100 dark:bg-gray-600 dark:border-gray-700 dark:hover:bg-gray-700">
    <h5 class="mb-2 text-xl font-bold text-center tracking-tight text-gray-900 dark:text-white">Produtos</h5>
    <div class="flex justify-center mt-4">
      <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-basket text-white" viewBox="0 0 16 16">
        <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1v4.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 13.5V9a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1.217L5.07 1.243a.5.5 0 0 1 .686-.172zM2 9v4.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V9zM1 7v1h14V7zm3 3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 4 10m2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 6 10m2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 10m2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5m2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5"/>
      </svg>
    </div>
  </a>


  <a href="{{ route('comandas.index') }}" class="block w-48 h-48 p-6 bg-white border border-gray-200 rounded-3xl shadow hover:bg-gray-100 dark:bg-gray-600 dark:border-gray-700 dark:hover:bg-gray-700">
    <h5 class="mb-2 text-xl font-bold text-center tracking-tight text-gray-900 dark:text-white">Comandas</h5>
    <div class="flex justify-center mt-4">
     <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-cash-stack text-white" viewBox="0 0 16 16">
        <path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1zm7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
        <path d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2z"/>
      </svg>
    </div>
  </a>
</div>


</body>
</html>