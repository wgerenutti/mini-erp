<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Produto;
use App\Models\Estoque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PedidoController extends Controller
{
    /**
     * Displays a listing of the orders.
     */
    public function index()
    {
        $pedidos = Pedido::with('creator')->orderByDesc('created_at')->paginate(15);
        return view('pedidos.index', compact('pedidos'));
    }

    /**
     * Shows the form to create a new order.
     */
    public function create()
    {
        //
    }

    /**
     * Stores a newly created order in the database.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Displays the specified order.
     */
    public function show(Pedido $pedido)
    {
        //
    }

    /**
     * Shows the form to edit the specified order.
     */
    public function edit(Pedido $pedido)
    {
        //
    }

    /**
     * Updates the specified order in the database.
     */
    public function update(Request $request, Pedido $pedido)
    {
        //
    }

    /**
     * Removes the specified order from the database.
     */
    public function destroy(Pedido $pedido)
    {
        DB::transaction(function () use ($pedido) {
            foreach ($pedido->itens as $item) {
                $estoque = $item->produto
                    ->estoque()
                    ->when($item->variacao_id, fn($q) => $q->where('variacao_id', $item->variacao_id))
                    ->when(!$item->variacao_id, fn($q) => $q->whereNull('variacao_id'))
                    ->first();

                if ($estoque) {
                    $estoque->quantidade += $item->quantidade;
                    $estoque->save();
                }
            }

            $pedido->itens()->delete();

            $pedido->delete();
        });

        return redirect()
            ->route('pedidos.index')
            ->with('success', 'Pedido e referências removidos com sucesso!');
    }

    /**
     * Adiciona um produto ao carrinho.
     */
    public function adicionar(Request $request, Produto $produto)
    {
        $items = session('carrinho.items', []);
        $key   = $produto->id . ':null';

        if (isset($items[$key])) {
            $items[$key]['quantidade']++;
        } else {
            $items[$key] = [
                'produto_id'  => $produto->id,
                'variacao_id' => null,
                'nome'        => $produto->nome,
                'preco'       => $produto->preco,
                'quantidade'  => 1,
            ];
        }

        $this->syncCartSession(
            $items,
            session('carrinho.cep'),
            session('carrinho.endereco')
        );

        $cartHtml = view('partials.cart_body')->render();
        return response()->json([
            'count'    => count(session('carrinho.items', [])),
            'cart_html' => $cartHtml,
        ], 200);
    }

    /**
     * Exibe o carrinho de compras.
     */
    public function carrinho()
    {
        $c = session('carrinho', [
            'items'    => [],
            'subtotal' => 0,
            'frete'    => 0,
            'total'    => 0,
            'cep'      => null,
            'endereco' => null,
        ]);

        return view('carrinho.show', [
            'cart'     => $c['items'],
            'subtotal' => $c['subtotal'],
            'frete'    => $c['frete'],
            'total'    => $c['total'],
            'cep'      => $c['cep'],
            'endereco' => $c['endereco'],
        ]);
    }

    /**
     * Finaliza o pedido, valida o CEP, salva o pedido e atualiza o estoque.
     */
    public function finalizar(Request $req)
    {
        $data = $req->validate([
            'cep' => 'required|regex:/^[0-9]{5}-?[0-9]{3}$/',
        ]);

        $cep = preg_replace('/[^0-9]/', '', $data['cep']);
        $resp = Http::get("https://viacep.com.br/ws/{$cep}/json/");

        if ($resp->failed() || isset($resp['erro'])) {
            return back()->withErrors(['cep' => 'CEP inválido']);
        }

        $endereco = $resp->json();
        $carrinho = session('carrinho');
        if (empty($carrinho['items'] ?? [])) {
            return back()->with('error', 'Carrinho vazio.');
        }

        $userId = Auth::id();

        DB::transaction(function () use ($cep, $endereco, $carrinho, $userId) {
            $pedido = Pedido::create([
                'cep'         => $cep,
                'endereco'    => "{$endereco['logradouro']}, " . ($endereco['bairro'] ?? '') . " - {$endereco['localidade']}/{$endereco['uf']}",
                'subtotal'    => $carrinho['subtotal'],
                'frete'       => $carrinho['frete'],
                'total'       => $carrinho['total'],
                'created_by'  => $userId,
            ]);

            foreach ($carrinho['items'] as $item) {
                PedidoItem::create([
                    'pedido_id'   => $pedido->id,
                    'produto_id'  => $item['produto_id'],
                    'variacao_id' => $item['variacao_id'],
                    'quantidade'  => $item['quantidade'],
                    'preco_unit'  => $item['preco'],
                    'total_item'  => $item['preco'] * $item['quantidade'],
                    'created_by'  => $userId,
                ]);

                $produto = Produto::findOrFail($item['produto_id']);
                $estoqueQuery = $produto->estoque()
                    ->when($item['variacao_id'], fn($q) => $q->where('variacao_id', $item['variacao_id']))
                    ->when(!$item['variacao_id'], fn($q) => $q->whereNull('variacao_id'));

                $estoque = $estoqueQuery->firstOrFail();
                if ($estoque->quantidade < $item['quantidade']) {
                    throw new \Exception("Estoque insuficiente para o produto {$produto->nome}");
                }
                $estoque->quantidade -= $item['quantidade'];
                $estoque->save();
            }
        });

        session()->forget('carrinho');

        return redirect()
            ->route('produtos.index')
            ->with('success', 'Pedido realizado com sucesso!');
    }

    /**
     * Recalcula e persiste todo o carrinho na sessão.
     */
    protected function syncCartSession(array $items, ?string $cep = null, ?array $endereco = null)
    {
        // subtotal
        $subtotal = array_sum(array_map(fn($i) => $i['preco'] * $i['quantidade'], $items));

        // frete
        if ($subtotal > 200)           $frete = 0;
        elseif ($subtotal >= 52)       $frete = 15;
        else                           $frete = 20;

        // total
        $total = $subtotal + $frete;

        session([
            'carrinho' => [
                'items'    => $items,
                'subtotal' => $subtotal,
                'frete'    => $frete,
                'total'    => $total,
                'cep'      => $cep,
                'endereco' => $endereco,
            ],
        ]);
    }

    /**
     * Define o CEP e endereço do carrinho.
     */
    public function setCep(Request $request)
    {
        $data = $request->validate([
            'cep' => 'required|regex:/^[0-9]{5}-?[0-9]{3}$/',
        ]);

        $cep = preg_replace('/\D/', '', $data['cep']);
        $resp = Http::get("https://viacep.com.br/ws/{$cep}/json/");

        if ($resp->failed() || isset($resp['erro'])) {
            return back()->withErrors(['cep' => 'CEP inválido']);
        }
        $endereco = $resp->json();

        $items = session('carrinho.items', []);

        $this->syncCartSession($items, $cep, $endereco);

        return back();
    }

    /**
     * Remove um item do carrinho.
     */
    public function remover(string $key)
    {
        $items = session('carrinho.items', []);
        unset($items[$key]);

        $this->syncCartSession(
            $items,
            session('carrinho.cep'),
            session('carrinho.endereco')
        );

        return back();
    }

    /**
     * Atualiza a quantidade de um item no carrinho via AJAX.
     */
    public function atualizarQuantidade(Request $request, string $key)
    {
        $data = $request->validate([
            'quantidade' => 'required|integer|min:1',
        ]);

        $carrinho = session('carrinho', [
            'items'    => [],
            'subtotal' => 0,
            'frete'    => 0,
            'total'    => 0,
            'cep'      => null,
            'endereco' => null,
        ]);

        if (! array_key_exists($key, $carrinho['items'])) {
            return response()->json(['error' => 'Item não encontrado'], 404);
        }

        $qty = $data['quantidade'];
        $carrinho['items'][$key]['quantidade'] = $qty;
        $carrinho['items'][$key]['subtotal']  = $carrinho['items'][$key]['preco'] * $qty;

        $this->syncCartSession(
            $carrinho['items'],
            $carrinho['cep'],
            $carrinho['endereco']
        );

        $cartHtml = view('partials.cart_body')->render();

        return response()->json([
            'count'     => count($carrinho['items']),
            'cart_html' => $cartHtml,
        ], 200);
    }
}
