@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">

            @include('partials.sidebar')

            <main class="col-md-9 col-lg-10 px-md-4">
                <div class="d-flex justify-content-between align-items-center py-3">
                    <h1 class="h3">Pedidos</h1>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table id="pedidosTable" class="table table-striped table-hover w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>CEP</th>
                            <th>Endereço</th>
                            <th>Subtotal (R$)</th>
                            <th>Frete (R$)</th>
                            <th>Total (R$)</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pedidos as $pedido)
                            <tr>
                                <td>{{ $pedido->id }}</td>
                                <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ substr($pedido->cep, 0, 5) . '-' . substr($pedido->cep, 5) }}</td>
                                <td>{{ $pedido->endereco }}</td>
                                <td>{{ number_format($pedido->subtotal, 2, ',', '.') }}</td>
                                <td>{{ number_format($pedido->frete, 2, ',', '.') }}</td>
                                <td>{{ number_format($pedido->total, 2, ',', '.') }}</td>
                                <td class="d-flex gap-1">
                                    <a href="{{ route('pedidos.show', $pedido) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('pedidos.destroy', $pedido) }}" method="POST"
                                        onsubmit="return confirm('Excluir pedido?')" class="d-inline">
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

                <div class="mt-4">
                    {{ $pedidos->links() }}
                </div>
            </main>

            @include('partials.cart_sidebar')

        </div>
    </div>
    @push('scripts')
        <script>
            new DataTable('#pedidosTable', {
                language: {
                    url: '//cdn.datatables.net/plug-ins/2.3.2/i18n/pt-BR.json'
                },
                paging: false,
                ordering: true,
                responsive: true
            });
        </script>
    @endpush
@endsection
