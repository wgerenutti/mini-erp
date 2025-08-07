<?php

namespace App\Http\Controllers;

use App\Models\Cupom;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCupomRequest;

class CupomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cupons = Cupom::orderByDesc('created_at')->paginate(15);
        return view('cupons.index', compact('cupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cupons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCupomRequest $request)
    {
        Cupom::create($request->validated());
        return redirect()->route('cupons.index')
            ->with('success', 'Cupom criado com sucesso!');
    }
    /**
     * Display the specified resource.
     */
    public function show(Cupom $cupom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cupom $cupom)
    {
        return view('cupons.edit', compact('cupom'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cupom $cupom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cupom $cupom)
    {
        //
    }

    /**
     * Alterna o status 'ativo' do cupom.
     */
    public function toggle(Cupom $cupom)
    {
        $cupom->ativo = ! $cupom->ativo;
        $cupom->save();

        $status = $cupom->ativo ? 'ativado' : 'desativado';
        return redirect()
            ->route('cupons.index')
            ->with('success', "Cupom “{$cupom->codigo}” {$status} com sucesso!");
    }
}
