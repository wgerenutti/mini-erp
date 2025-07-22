@extends('layouts.app')

@section('header')
    <h1>Editar Produto</h1>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    <form action="{{ route('produtos.update', $produto) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Produto</label>
            <input type="text" name="nome" id="nome" class="form-control @error('nome') is-invalid @enderror"
                value="{{ old('nome', $produto->nome) }}" required>
            @error('nome')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="preco" class="form-label">Preço (R$)</label>
            <input type="text" id="preco" name="preco"
                class="form-control money @error('preco') is-invalid @enderror"
                value="{{ old('preco', isset($produto) ? number_format($produto->preco, 2, ',', '.') : '') }}" required />
            @error('preco')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        @if ($produto->estoque)
            <div class="mb-3">
                <label for="quantidade" class="form-label">Estoque</label>
                <input type="number" name="quantidade" id="quantidade" class="form-control"
                    value="{{ old('quantidade', $produto->estoque->quantidade) }}" disabled>
            </div>
        @endif

        <div class="d-flex justify-content-between">
            <a href="{{ route('produtos.index') }}" class="btn btn-danger">Voltar</a>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#preco').mask('#.##0,00', {
                reverse: true
            });
        });
    </script>
@endpush
