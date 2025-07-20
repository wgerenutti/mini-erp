<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Produto;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Pedido $pedido)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pedido $pedido)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pedido $pedido)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pedido $pedido)
    {
        //
    }

    public function adicionar(Request $req)
    {
        $prod = Produto::findOrFail($req->produto_id);
        $cart = session()->get('cart', []);
        $cart[$prod->id] = [
            'nome'     => $prod->nome,
            'quantidade' => ($cart[$prod->id]['quantidade'] ?? 0) + 1,
            'preco'    => $prod->preco,
        ];
        session(['cart' => $cart]);
        return back()->with('success', 'Adicionado ao carrinho');
    }

    public function carrinho()
    {
        $cart = session('cart', []);
        $subtotal = array_sum(array_map(fn($i) => $i['preco'] * $i['quantidade'], $cart));
        if ($subtotal > 200)        $frete = 0;
        elseif ($subtotal >= 52)    $frete = 15;
        else                        $frete = 20;
        return view('carrinho.show', compact('cart', 'subtotal', 'frete'));
    }

    public function finalizar(Request $req)
    {
        $data = $req->validate([
            'cep' => 'required',
            'endereco_completo' => 'required'
        ]);
        $resp = Http::get("https://viacep.com.br/ws/{$data['cep']}/json/");
        if (isset($resp['erro'])) {
            return back()->withErrors(['cep' => 'CEP inválido']);
        }
    }
}
