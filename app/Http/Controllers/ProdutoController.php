<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProdutoRequest;
use App\Http\Requests\StoreProdutoRequest;
use Illuminate\Support\Facades\DB;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produtos = Produto::with('estoque', 'variacoes')->paginate(15);
        return view('produtos.index', compact('produtos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('produtos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProdutoRequest $req)
    {
        DB::transaction(function () use ($req) {
            $produto = Produto::create($req->validated());

            $produto->estoque()->create([
                'quantidade'  => $req->input('quantidade', 0),
                'variacao_id' => null,
            ]);

            foreach ($req->input('variacoes', []) as $var) {
                $precoVar = floatval($var['preco'] ?? 0) > 0
                    ? $var['preco']
                    : $produto->preco;

                $v = $produto->variacoes()->create([
                    'nome'  => $var['nome'],
                    'preco' => $precoVar,
                ]);

                $produto->estoque()->create([
                    'quantidade'  => $var['quantidade'] ?? 0,
                    'variacao_id' => $v->id,
                ]);
            }
        });

        return redirect()->route('produtos.index')
            ->with('success', 'Produto criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Produto $produto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produto $produto)
    {
        $produto->load('estoque', 'variacoes');
        return view('produtos.edit', compact('produto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProdutoRequest $req, Produto $produto)
    {
        DB::transaction(function () use ($req, $produto) {
            $produto->update($req->validated());

            $estoqueGeral = $produto->estoque()->firstOrNew(['variacao_id' => null]);
            $estoqueGeral->quantidade = $req->input('quantidade', 0);
            $estoqueGeral->save();

            $submitted  = collect($req->input('variacoes', []));
            $currentIds = $produto->variacoes()->pluck('id');

            $toDelete = $currentIds->diff($submitted->pluck('id')->filter());
            $produto->variacoes()->whereIn('id', $toDelete)->delete();

            $submitted->each(function ($varData) use ($produto) {
                $precoVar = floatval($varData['preco'] ?? 0) > 0
                    ? $varData['preco']
                    : $produto->preco;

                if (!empty($varData['id'])) {
                    $v = $produto->variacoes()->find($varData['id']);
                    $v->update([
                        'nome'  => $varData['nome'],
                        'preco' => $precoVar,
                    ]);
                } else {
                    $v = $produto->variacoes()->create([
                        'nome'  => $varData['nome'],
                        'preco' => $precoVar,
                    ]);
                }

                $produto->estoque()->updateOrCreate(
                    ['variacao_id' => $v->id],
                    ['quantidade'  => $varData['quantidade'] ?? 0]
                );
            });
        });

        return redirect()->route('produtos.index')
            ->with('success', 'Produto atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produto $produto)
    {
        $produto->delete();
        return back()->with('success', 'Produto removido');
    }
}
