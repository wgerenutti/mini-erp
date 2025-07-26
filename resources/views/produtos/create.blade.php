@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="h3 mb-4">Novo Produto</h1>
        <form method="POST" action="{{ route('produtos.store') }}">
            @csrf
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" name="nome" id="nome" value="{{ old('nome') }}"
                    class="form-control @error('nome') is-invalid @enderror" required>
                @error('nome')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Preço --}}
            <div class="mb-3">
                <label for="preco" class="form-label">Preço (R$)</label>
                <input type="text" name="preco" id="preco"
                    class="form-control money @error('preco') is-invalid @enderror" value="{{ old('preco') }}" required>
                @error('preco')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Estoque Geral --}}
            <div class="mb-3">
                <label for="quantidade" class="form-label">Estoque Inicial</label>
                <input type="number" name="quantidade" id="quantidade" value="{{ old('quantidade', 0) }}"
                    class="form-control" min="0">
            </div>

            {{-- Variações --}}
            <div id="variacoes-section" class="mb-4">
                <h5>Variações</h5>
                <button type="button" id="add-var" class="btn btn-sm btn-outline-primary mb-2">Adicionar
                    Variação</button>
                <div id="variacoes-list"></div>
            </div>

            <button class="btn btn-primary">Salvar</button>
            <a href="{{ route('produtos.index') }}" class="btn btn-danger ms-2">Cancelar</a>
    </div>
    @push('scripts')
        <script>
            $(function() {
                $('.money').mask('000.000.000.000,00', {
                    reverse: true
                });

                $('#add-var').on('click', function() {
                    const idx = $('#variacoes-list .variation-item').length;
                    const html = `
                        <div class="card mb-2 p-2 variation-item">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                            <input type="text" name="variacoes[${idx}][nome]" placeholder="Nome da variação" class="form-control mb-1">
                            </div>
                            <div class="col-md-3">
                            <input type="text" name="variacoes[${idx}][preco]" placeholder="Preço (opcional)" class="form-control money mb-1">
                            </div>
                            <div class="col-md-3">
                            <input type="number" name="variacoes[${idx}][quantidade]" placeholder="Estoque" class="form-control mb-1" min="0">
                            </div>
                            <div class="col-md-2 text-end">
                            <button type="button" class="btn-close remove-var" aria-label="Remover"></button>
                            </div>
                        </div>
                        </div>`;
                    $('#variacoes-list').append(html);
                    $('.money').mask('000.000.000.000,00', {
                        reverse: true
                    });
                });

                $(document).on('click', '.remove-var', function() {
                    $(this).closest('.variation-item').remove();
                });
            });
        </script>
    @endpush
@endsection
