<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuartoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ComandaController;
use App\Http\Controllers\ComandaProdutoController;


// Rotas de Cliente
Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
Route::put('/clientes/{id}', [ClienteController::class, 'update'])->name('clientes.update');
Route::delete('/clientes/{id}', [ClienteController::class, 'destroy'])->name('clientes.destroy');

// Rotas de Produto
Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos.index');
Route::post('/produtos', [ProdutoController::class, 'store'])->name('produtos.store');
Route::put('/produtos/{id}', [ProdutoController::class, 'update'])->name('produtos.update');
Route::delete('/produtos/{id}', [ProdutoController::class, 'destroy'])->name('produtos.destroy');

// Rotas de Quarto
Route::get('/quartos', [QuartoController::class, 'index'])->name('quartos.index');
Route::post('/quartos', [QuartoController::class, 'store'])->name('quartos.store');
Route::put('/quartos/{id}', [QuartoController::class, 'update'])->name('quartos.update');
Route::delete('/quartos/{id}', [QuartoController::class, 'destroy'])->name('quartos.destroy');

// Rotas de Status
Route::post('/statuses', [StatusController::class, 'store'])->name('statuses.store');
Route::get('/statuses', [StatusController::class, 'index'])->name('statuses.index');

// Rotas de Reserva
Route::get('/reservas', [ReservationController::class, 'index'])->name('reservas.index');
Route::post('/reservas', [ReservationController::class, 'store'])->name('reservas.store');
Route::put('/reservas/{reserva}', [ReservationController::class, 'update'])->name('reservas.update');
Route::put('/reservas/{id}/finalizar', [ReservationController::class, 'finalizar'])->name('reservas.finalizar');

// Rotas de Comanda
Route::get('/comandas', [ComandaController::class, 'index'])->name('comandas.index');
Route::post('/comandas', [ComandaController::class, 'store'])->name('comandas.store');
Route::get('/comandas/{comanda}', [ComandaController::class, 'show'])->name('comandas.show');
Route::put('/comandas/{comanda}', [ComandaController::class, 'update'])->name('comandas.update');
Route::delete('/comandas/{comanda}', [ComandaController::class, 'destroy'])->name('comandas.destroy');

// Adicionar produto à comanda
Route::post('/comandas/adicionar-produto', [ComandaProdutoController::class, 'store'])->name('comanda.adicionarProduto');
Route::delete('/comandas/produtos/{id}', [ComandaProdutoController::class, 'destroy'])->name('comanda.produto.destroy');
Route::get('/comandas', [ComandaController::class, 'index'])->name('comandas.index');
Route::get('/comandas/{comanda}', [ComandaController::class, 'show'])->name('comandas.show');
Route::delete('/comandas/{comanda}/produtos/{produto}', [ComandaProdutoController::class, 'destroy'])->name('comanda.removerProduto');





Route::get('/', function () {
    return view('welcome');
});


