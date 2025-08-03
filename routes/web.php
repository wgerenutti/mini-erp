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
    Route::post('carrinho/{produto}/adicionar', [PedidoController::class, 'adicionar'])->name('carrinho.adicionar');
    Route::post('carrinho/cep', [PedidoController::class, 'setCep'])->name('carrinho.cep');
    Route::delete('carrinho/remover', [PedidoController::class, 'remover'])->name('carrinho.remover');
    Route::patch('carrinho/{key}', [PedidoController::class, 'atualizarQuantidade'])->name('carrinho.atualizar');
    Route::delete('carrinho/{key}/remover', [PedidoController::class, 'remover'])->name('carrinho.remover');
    Route::post('webhook/pedido', [PedidoController::class, 'webhook'])->name('pedido.webhook');
    Route::patch('carrinho/{key}', [PedidoController::class, 'atualizarQuantidade'])->name('carrinho.atualizar');
    Route::resource('pedidos', PedidoController::class)->except(['create','store','edit','update']);
    Route::post('pedidos/finalizar', [PedidoController::class, 'finalizar'])->name('pedidos.finalizar');
});

require __DIR__ . '/auth.php';
