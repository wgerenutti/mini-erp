@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">

            @include('partials.sidebar')

            {{-- Main content --}}
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3">Produtos</h1>
                    <a href="{{ route('produtos.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-lg me-1"></i> Novo Produto
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table id="produtosTable" class="table table-striped table-hover w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Preço (R$)</th>
                            <th>Estoque</th>
                            <th>Variações</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produtos as $produto)
                            <tr>
                                <td>{{ $produto->id }}</td>
                                <td>{{ $produto->nome }}</td>
                                <td>{{ number_format($produto->preco, 2, ',', '.') }}</td>
                                <td>{{ $produto->estoque->whereNull('variacao_id')->first()->quantidade ?? '-' }}</td>
                                <td>
                                    @foreach ($produto->variacoes as $variacao)
                                        <span class="badge bg-secondary">
                                            {{ $variacao->nome }} ({{ $variacao->estoque->quantidade ?? 0 }})
                                        </span>
                                    @endforeach
                                </td>
                                <td>
                                    <a href="{{ route('produtos.edit', $produto) }}" class="btn btn-sm btn-primary me-1"><i
                                            class="bi bi-pencil-fill"></i></a>
                                    <form action="{{ route('produtos.destroy', $produto) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Excluir produto?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-3">{{ $produtos->links() }}</div>
            </main>
        </div>
    </div>
@endsection

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
