<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\VariacaoController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\CupomController;
use App\Http\Controllers\PedidoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('produtos', ProdutoController::class);
    Route::resource('variacoes', VariacaoController::class)->shallow();
    Route::resource('estoque', EstoqueController::class)->only(['index', 'update']);
    Route::resource('cupons', CupomController::class)->except(['show']);
    Route::get('carrinho', [PedidoController::class, 'carrinho'])->name('carrinho.show');
    Route::post('carrinho/adicionar', [PedidoController::class, 'adicionar'])->name('carrinho.adicionar');
    Route::get('pedido', [PedidoController::class, 'index'])->name('pedido.index');
    Route::post('carrinho/remover', [PedidoController::class, 'remover'])->name('carrinho.remover');
    Route::post('pedido/finalizar', [PedidoController::class, 'finalizar'])->name('pedido.finalizar');
    Route::post('webhook/pedido', [PedidoController::class, 'webhook'])->name('pedido.webhook');
});

require __DIR__ . '/auth.php';
