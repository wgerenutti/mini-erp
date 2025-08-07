@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('partials.sidebar')
            <main class="col-md-9 col-lg-10 px-md-4">

                <div class="d-flex justify-content-between align-items-center py-3">
                    <h1 class="h3">Cupons</h1>
                    <div>
                        <a href="{{ route('cupons.create') }}" class="btn btn-success mb-3">
                            <i class="bi bi-plus-lg me-1"></i>Novo Cupom</a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table id="cuponsTable" class="table table-striped table-hover w-100">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Desconto</th>
                            <th>Valor Mínimo</th>
                            <th>Validade</th>
                            <th>Ativo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cupons as $c)
                            <tr>
                                <td>{{ $c->codigo }}</td>

                                <td>
                                    @if ($c->valor_desc)
                                        R$ {{ number_format($c->valor_desc, 2, ',', '.') }}
                                    @elseif($c->pct_desc)
                                        {{ number_format($c->pct_desc, 2, ',', '.') }} %
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>R$ {{ number_format($c->minimo, 2, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($c->validade)->format('d/m/Y') }}</td>
                                <td>{{ $c->ativo ? 'Sim' : 'Não' }}</td>

                                <td class="d-flex gap-1">
                                    <a href="{{ route('cupons.edit', $c->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('cupons.toggle', $c->id) }}" method="POST"
                                        onsubmit="return confirm('Deseja realmente @if ($c->ativo) desativar @else ativar @endif este cupom?')"
                                        class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        @if ($c->ativo)
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-toggle-off me-1"></i>Desativar
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-toggle-on me-1"></i>Ativar
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </main>
        </div>
    </div>
    {{ $cupons->links() }}

    @push('scripts')
        <script>
            new DataTable('#cuponsTable', {
                language: {
                    url: '/vendor/datatables/i18n/pt-BR.json'
                },
                paging: false,
                ordering: true,
                responsive: true
            });
        </script>
    @endpush
@endsection
