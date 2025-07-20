<form method="POST" action="{{ route('produtos.store') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Nome</label>
        <input name="nome" value="{{ old('nome') }}" class="form-control @error('nome') is-invalid @enderror">
        @error('nome')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <!-- Preço -->
    <div class="mb-3">
        <label class="form-label">Preço</label>
        <input name="preco" type="number" step="0.01" value="{{ old('preco') }}"
            class="form-control @error('preco') is-invalid @enderror">
        @error('preco')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <!-- Quantidade inicial -->
    <div class="mb-3">
        <label class="form-label">Quantidade Inicial</label>
        <input name="quantidade_inicial" type="number" value="{{ old('quantidade_inicial', 0) }}" class="form-control">
    </div>
    <!-- Se quiser variações via JS, repita grupos de inputs aqui -->
    <button class="btn btn-primary">Salvar</button>
</form>
