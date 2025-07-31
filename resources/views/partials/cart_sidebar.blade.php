@php
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

<div class="offcanvas offcanvas-end" id="cartCanvas" tabindex="-1" aria-labelledby="cartCanvasLabel">
    <div class="offcanvas-header">
        <h5 id="cartCanvasLabel">Seu Carrinho</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        @include('partials.cart_body')
    </div>
</div>
