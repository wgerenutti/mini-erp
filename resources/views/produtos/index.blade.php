@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Produtos</h1>
            <a href="{{ route('produtos.create') }}" class="btn btn-success">
                <i class="bi bi-plus-lg me-1"></i> Novo Produto
            </a>
        </div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table id="produtosTable" class="table table-striped table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Preço (R$)</th>
                    <th>Estoque</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($produtos as $produto)
                    <tr>
                        <td>{{ $produto->id }}</td>
                        <td>{{ $produto->nome }}</td>
                        <td>{{ number_format($produto->preco, 2, ',', '.') }}</td>
                        <td>{{ $produto->estoque->quantidade ?? '-' }}</td>
                        <td>
                            <a href="{{ route('produtos.edit', $produto) }}" class="btn btn-sm btn-primary me-1">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form action="{{ route('produtos.destroy', $produto) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Excluir produto?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $produtos->links() }}
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#produtosTable').DataTable({
                    paging: false,
                    info: false,
                    searching: true,
                    order: [
                        [1, 'asc']
                    ],
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-PT.json"
                    },
                });
            });
        </script>
    @endpush
@endsection
