<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuartoController;

Route::get('/', function () {
    return redirect()->route('gerenciar-reserva');
});

Route::get('/gerenciar-reserva', [QuartoController::class, 'index'])->name('gerenciar-reserva');
Route::get('/adicionar-quarto', function () {
    return view('AdicionarQuarto');
})->name('adicionar-quarto-form');

Route::post('/adicionar-quarto', [QuartoController::class, 'store'])->name('adicionar-quarto');

Route::get('/{id}/edit', [QuartoController::class, 'edit'])->name('editar-quarto');

Route::put('/{id}', [QuartoController::class, 'update'])->name('atualizar-quarto');



