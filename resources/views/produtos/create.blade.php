@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="h3 mb-4">Novo Produto</h1>
        <form method="POST" action="{{ route('produtos.store') }}">
            @csrf
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" id="nome" name="nome" value="{{ old('nome') }}"
                    class="form-control @error('nome') is-invalid @enderror" required>
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
            <div class="mb-3">
                <label for="quantidade_inicial" class="form-label">Quantidade Inicial</label>
                <input type="number" id="quantidade_inicial" name="quantidade_inicial"
                    value="{{ old('quantidade_inicial', 0) }}" class="form-control" min="0">
            </div>
            <button class="btn btn-primary">Salvar</button>
            <a href="{{ route('produtos.index') }}" class="btn btn-danger ms-2">Cancelar</a>
        </form>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.money').mask('000.000.000.000,00', {
                    reverse: true
                });
            });
        </script>
    @endpush
@endsection
