@extends('layouts.app')

@section('header')
    <h1>Editar Produto</h1>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('produtos.update', $produto) }}" method="POST">
        @csrf @method('PUT')

        {{-- Nome --}}
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" name="nome" id="nome" value="{{ old('nome', $produto->nome) }}"
                class="form-control @error('nome') is-invalid @enderror" required>
            @error('nome')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Preço --}}
        <div class="mb-3">
            <label for="preco" class="form-label">Preço (R$)</label>
            <input type="text" name="preco" id="preco"
                value="{{ old('preco', number_format($produto->preco, 2, ',', '.')) }}"
                class="form-control money @error('preco') is-invalid @enderror" required>
            @error('preco')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Estoque Geral --}}
        <div class="mb-3">
            <label for="quantidade" class="form-label">Estoque Geral</label>
            <input type="number" name="quantidade" id="quantidade"
                value="{{ old('quantidade', $produto->estoque->whereNull('variacao_id')->first()->quantidade ?? 0) }}"
                class="form-control" min="0">
        </div>

        {{-- Variações --}}
        <div id="variacoes-section" class="mb-4">
            <h5>Variações</h5>
            <button type="button" id="add-var" class="btn btn-sm btn-outline-primary mb-2">
                Adicionar Variação
            </button>
            <div id="variacoes-list">
                @foreach (old('variacoes', $produto->variacoes) as $i => $var)
                    <div class="card mb-2 p-2 variation-item">
                        <input type="hidden" name="variacoes[{{ $i }}][id]" value="{{ $var->id }}">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <input type="text" name="variacoes[{{ $i }}][nome]"
                                    value="{{ old("variacoes.$i.nome", $var->nome) }}" placeholder="Nome da variação"
                                    class="form-control mb-1">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="variacoes[{{ $i }}][preco]"
                                    value="{{ old("variacoes.$i.preco", number_format($var->preco, 2, ',', '.')) }}"
                                    placeholder="Preço (opcional)" class="form-control money mb-1">
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="variacoes[{{ $i }}][quantidade]"
                                    value="{{ old("variacoes.$i.quantidade", $var->estoque->quantidade ?? 0) }}"
                                    placeholder="Estoque" class="form-control mb-1" min="0">
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="button" class="btn-close remove-var" aria-label="Remover"></button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <button class="btn btn-primary">Atualizar</button>
        <a href="{{ route('produtos.index') }}" class="btn btn-danger ms-2">Cancelar</a>
    </form>
@endsection

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
                        <button type="button" class="btn-close remove-var"></button>
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
