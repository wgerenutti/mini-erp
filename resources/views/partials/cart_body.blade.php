@php
    use App\Models\Produto;

    $c = array_merge(
        [
            'items' => [],
            'subtotal' => 0,
            'frete' => 0,
            'total' => 0,
            'cep' => null,
            'endereco' => null,
        ],
        session('carrinho', []),
    );
@endphp

@if (empty($c['items']))
    <p class="text-center text-muted">Carrinho vazio</p>
@else
    <ul class="list-group mb-3">
        @foreach ($c['items'] as $key => $item)
            @php
                $produto = Produto::find($item['produto_id']);
                $estoqueModel = $produto
                    ->estoque()
                    ->when($item['variacao_id'], fn($q) => $q->where('variacao_id', $item['variacao_id']))
                    ->when(!$item['variacao_id'], fn($q) => $q->whereNull('variacao_id'))
                    ->first();
                $maxQty = $estoqueModel->quantidade ?? 0;
            @endphp
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $item['nome'] }}</strong><br>
                    R$ {{ number_format($item['preco'], 2, ',', '.') }} ×
                    <div class="input-group input-group-sm d-inline-flex align-items-center position-relative"
                        style="width: 105px;">
                        <button class="btn btn-outline-secondary btn-decr" data-key="{{ $key }}"
                            type="button">–</button>
                        <input type="number" class="form-control text-center quantity-input"
                            value="{{ $item['quantidade'] }}" data-key="{{ $key }}"
                            data-max="{{ $maxQty }}" min="1" max="{{ $maxQty }}" step="1" />
                        <button class="btn btn-outline-secondary btn-incr" data-key="{{ $key }}"
                            type="button">+</button>

                        <div class="spinner-border spinner-border-sm position-absolute top-50 start-50 translate-middle d-none loading-qty"
                            role="status">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                    </div>

                    <form action="{{ route('carrinho.remover', $key) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger p-1"><i class="bi bi-x-lg"></i></button>
                    </form>
            </li>
        @endforeach
    </ul>

    <form action="{{ route('carrinho.cep') }}" method="POST" class="mb-3">
        @csrf
        <div class="input-group">
            <input type="text" name="cep" class="form-control cep-mask" placeholder="00000-000"
                value="{{ old('cep', $c['cep']) }}" required>
            <button class="btn btn-outline-secondary">OK</button>
        </div>
        @if ($c['endereco'])
            <small class="text-muted">{{ $c['endereco']['logradouro'] }}, {{ $c['endereco']['bairro'] }} –
                {{ $c['endereco']['localidade'] }}/{{ $c['endereco']['uf'] }}</small>
        @endif
    </form>

    <div class="mb-3">
        <div class="d-flex justify-content-between"><span>Subtotal:</span><strong>R$
                {{ number_format($c['subtotal'], 2, ',', '.') }}</strong></div>
        <div class="d-flex justify-content-between"><span>Frete:</span><strong>R$
                {{ number_format($c['frete'], 2, ',', '.') }}</strong></div>

        @if (isset($c['cupom']))
            <div class="d-flex justify-content-between align-items-center mt-2">
                <div>
                    <strong>Cupom: {{ $c['cupom']['codigo'] }}</strong>
                    <div class="small text-muted">Desconto aplicado</div>
                </div>
                <div>
                    <strong>- R$ {{ number_format($c['cupom']['desconto_aplicado'], 2, ',', '.') }}</strong>
                    <button id="remove-cupom-btn" class="btn btn-sm btn-link text-danger ms-2">Remover</button>
                </div>
            </div>
        @else
            <form id="apply-cupom-form" class="d-flex gap-2 mt-3">
                <input type="text" name="codigo" class="form-control form-control-sm" placeholder="Código do cupom"
                    required>
                <button class="btn btn-sm btn-outline-primary" type="submit">
                    Aplicar
                    <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                </button>
            </form>
        @endif

        <hr>
        <div class="d-flex justify-content-between"><span>Total:</span><strong>R$
                {{ number_format($c['total'], 2, ',', '.') }}</strong></div>
    </div>

    <form action="{{ route('pedidos.finalizar') }}" method="POST" class="mt-auto finalize-form">
        @csrf
        <input type="hidden" name="cep" value="{{ $c['cep'] }}">

        @php $hasCep = (bool) $c['cep']; @endphp

        <span
            @if (!$hasCep) title="Informe o CEP antes de finalizar" data-bs-toggle="tooltip" data-bs-placement="top" @endif
            class="d-block w-100">
            <button type="submit"
                class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-2 finalize-btn"
                @if (!$hasCep) disabled aria-disabled="true" @endif aria-live="polite"
                aria-busy="false">
                <span class="finalize-spinner spinner-border spinner-border-sm d-none" role="status"
                    aria-hidden="true"></span>
                <span class="finalize-text">Finalizar Pedido</span>
            </button>
        </span>
    </form>
@endif
