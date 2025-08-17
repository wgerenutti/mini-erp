@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('partials.sidebar')

            <main class="col-md-9 col-lg-10 px-md-4">
                <div class="d-flex justify-content-between align-items-center py-3">
                    <h1 class="h3">Pedido #{{ $pedido->id }}</h1>
                    <a href="{{ route('pedidos.index') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-arrow-left-circle me-1"></i> Voltar
                    </a>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-4"><strong>Data:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}
                            </div>
                            <div class="col-md-4"><strong>CEP:</strong>
                                {{ substr($pedido->cep, 0, 5) . '-' . substr($pedido->cep, 5) }}</div>
                            <div class="col-md-4"><strong>Endereço:</strong> {{ $pedido->endereco }}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><strong>Subtotal:</strong> R$
                                {{ number_format($pedido->subtotal, 2, ',', '.') }}</div>
                            <div class="col-md-4"><strong>Frete:</strong> R$
                                {{ number_format($pedido->frete, 2, ',', '.') }}
                            </div>
                            <div class="col-md-4"><strong>Total:</strong> R$
                                {{ number_format($pedido->total, 2, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                @if ($pedido->cupons && $pedido->cupons->isNotEmpty())
                    <hr class="my-3">
                    <h6>Cupons aplicados</h6>
                    @foreach ($pedido->cupons as $cupom)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong>{{ $cupom->codigo }}</strong>
                                <div class="small text-muted">
                                    @if (!empty($cupom->validade))
                                        Validade: {{ \Carbon\Carbon::parse($cupom->validade)->format('d/m/Y') }} —
                                    @endif
                                    @if (!is_null($cupom->pct_desc))
                                        {{ number_format($cupom->pct_desc, 2, ',', '.') }}% (percentual)
                                    @elseif(!is_null($cupom->valor_desc))
                                        R$ {{ number_format($cupom->valor_desc, 2, ',', '.') }} (valor)
                                    @endif
                                </div>
                            </div>

                            <div class="text-end">
                                <div class="text-muted small">Desconto aplicado</div>
                                <strong>- R$
                                    {{ number_format($cupom->pivot->desconto_aplicado ?? 0, 2, ',', '.') }}</strong>

                                @if (Route::has('cupons.edit'))
                                    <div class="mt-1">
                                        <a href="{{ route('cupons.edit', $cupom->id) }}"
                                            class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-pencil"></i> Ver cupom
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif

                <h5 class="mb-3">Itens do Pedido</h5>
                @if ($pedido->itens->isEmpty())
                    <p class="text-muted">Nenhum item neste pedido.</p>
                @else
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Produto</th>
                                    <th>Variação</th>
                                    <th class="text-center">Qtd.</th>
                                    <th class="text-end">Preço Unit.</th>
                                    <th class="text-end">Total Item</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pedido->itens as $idx => $item)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td>{{ $item->produto->nome }}</td>
                                        <td>{{ $item->variacao->nome ?? '—' }}</td>
                                        <td class="text-center">{{ $item->quantidade }}</td>
                                        <td class="text-end">R$ {{ number_format($item->preco_unit, 2, ',', '.') }}</td>
                                        <td class="text-end">R$ {{ number_format($item->total_item, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="d-flex justify-content-end">
                    <form action="{{ route('pedidos.destroy', $pedido) }}" method="POST"
                        onsubmit="return confirm('Deseja realmente excluir este pedido?')" class="me-2">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">
                            <i class="bi bi-trash me-1"></i> Excluir Pedido
                        </button>
                    </form>
                </div>
            </main>

            @include('partials.cart_sidebar')
        </div>
    </div>
@endsection
