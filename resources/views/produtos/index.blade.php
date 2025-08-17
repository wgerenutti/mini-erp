@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">

            @include('partials.sidebar')

            <main class="col-md-9 col-lg-10 px-md-4">
                <div class="d-flex justify-content-between align-items-center py-3">
                    <h1 class="h3">Produtos</h1>
                    <div>
                        <a href="{{ route('produtos.create') }}" class="btn btn-success me-2">
                            <i class="bi bi-plus-lg me-1"></i> Novo Produto
                        </a>
                        <a href="#" class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#cartCanvas"
                            aria-controls="cartCanvas">
                            <i class="bi bi-cart3 me-1"></i>
                            <span id="cart-toggle-badge" class="badge bg-light text-dark">
                                {{ count(data_get(session('carrinho'), 'items', [])) }}
                            </span>
                        </a>

                    </div>
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
                                <td>{{ $produto->estoque->quantidade ?? '-' }}</td>
                                <td>
                                    @foreach ($produto->variacoes as $v)
                                        <span class="badge bg-secondary">
                                            {{ $v->nome }} ({{ $v->estoque->quantidade ?? 0 }})
                                        </span>
                                    @endforeach
                                </td>
                                <td class="d-flex gap-1">
                                    <a href="{{ route('produtos.edit', $produto) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('produtos.destroy', $produto) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Excluir produto?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                    <form action="{{ route('carrinho.adicionar', $produto) }}" method="POST"
                                        class="d-inline add-to-cart-form">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning position-relative">
                                            <span
                                                class="spinner-border spinner-border-sm position-absolute top-50 start-50 translate-middle d-none"
                                                role="status" aria-hidden="true"></span>
                                            <i class="bi bi-cart-plus"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">{{ $produtos->links() }}</div>
            </main>

            @include('partials.cart_sidebar')

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.cep-mask').mask('00000-000');

            function reaplicaMaskCep() {
                $('.cep-mask').mask('00000-000');
            }

            function showQtyLoader($group) {
                $group.find('.loading-qty').removeClass('d-none');
                $group.find('.btn-incr, .btn-decr, .quantity-input').prop('disabled', true);
            }

            function hideQtyLoader($group) {
                $group.find('.loading-qty').addClass('d-none');
                $group.find('.btn-incr, .btn-decr, .quantity-input').prop('disabled', false);
            }

            const offcanvasEl = document.getElementById('cartCanvas');

            document.querySelectorAll('.add-to-cart-form').forEach(form => {
                form.addEventListener('submit', async e => {
                    e.preventDefault();

                    const btn = form.querySelector('button');
                    const spinner = btn.querySelector('.spinner-border');

                    spinner.classList.remove('d-none');
                    btn.disabled = true;

                    const url = form.action;
                    const token = document.querySelector('meta[name="csrf-token"]').content;

                    try {
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                            }
                        });
                        if (!res.ok) throw new Error('Erro ao adicionar');
                        const data = await res.json();

                        document.getElementById('cart-toggle-badge').textContent = data.count;
                        document.querySelector('#cartCanvas .offcanvas-body')
                            .innerHTML = data.cart_html;

                        const off = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
                        off.show();
                        reaplicaMaskCep();
                    } catch (err) {
                        console.error(err);
                        alert('Não foi possível adicionar ao carrinho.');
                    } finally {
                        spinner.classList.add('d-none');
                        btn.disabled = false;
                    }
                });
            });

            @if (count(data_get(session('carrinho'), 'items', [])) > 0)
                const off = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
                off.show();
            @endif

            function updateCartQuantity(key, newQty) {
                const token = document.querySelector('meta[name="csrf-token"]').content;
                return fetch(`/carrinho/${encodeURIComponent(key)}`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        quantidade: newQty
                    })
                }).then(res => {
                    if (!res.ok) throw new Error('Falha ao atualizar quantidade');
                    return res.json();
                });
            }

            $(document).on('click', '.btn-incr, .btn-decr', function() {
                const key = this.dataset.key;
                const $group = $(this).closest('.position-relative');
                let $input = $group.find('.quantity-input');
                const maxQty = parseInt($input.data('max'), 10);
                let qty = parseInt($input.val(), 10);

                if (this.classList.contains('btn-incr')) {
                    if (qty >= maxQty) return;
                    qty++;
                } else {
                    if (qty <= 1) return;
                    qty--;
                }
                $input.val(qty);

                showQtyLoader($group);

                updateCartQuantity(key, qty)
                    .then(data => {
                        $('#cart-toggle-badge').text(data.count);
                        $('#cartCanvas .offcanvas-body').html(data.cart_html);
                        reaplicaMaskCep();
                    })
                    .catch(() => alert('Erro ao atualizar quantidade'))
                    .finally(() => {});
            });

            $(document).on('change', '.quantity-input', function() {
                const $input = $(this);
                const key = $input.data('key');
                let qty = parseInt($input.val(), 10);
                const maxQty = parseInt($input.attr('max'), 10);

                if (isNaN(qty) || qty < 1) {
                    qty = 1;
                } else if (qty > maxQty) {
                    qty = maxQty;
                }
                $input.val(qty);

                updateCartQuantity(key, qty)
                    .then(data => {
                        $('#cart-toggle-badge').text(data.count);
                        $('#cartCanvas .offcanvas-body').html(data.cart_html);
                    })
                    .catch(() => alert('Erro ao atualizar quantidade'));
            });

            $(document).on('blur keypress', '.quantity-input', function(e) {
                if (e.type === 'keypress' && e.which !== 13) return;

                const $input = $(this);
                const key = $input.data('key');
                let qty = parseInt($input.val(), 10) || 1;
                const maxQty = parseInt($input.attr('max'), 10);

                if (qty < 1) qty = 1;
                else if (qty > maxQty) qty = maxQty;
                $input.val(qty);

                const $group = $input.closest('.position-relative');
                showQtyLoader($group);

                updateCartQuantity(key, qty)
                    .then(data => {
                        $('#cart-toggle-badge').text(data.count);
                        $('#cartCanvas .offcanvas-body').html(data.cart_html);
                    })
                    .catch(() => alert('Erro ao atualizar quantidade'));
            });

            new DataTable('#produtosTable', {
                language: {
                    url: '/vendor/datatables/i18n/pt-BR.json'
                },
                paging: false,
                ordering: true,
                responsive: true
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
                new bootstrap.Tooltip(el);
            });

            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (!form.classList || !form.classList.contains('finalize-form')) return;

                if (form.dataset.sending === '1') {
                    e.preventDefault();
                    return;
                }

                const cepInput = form.querySelector('input[name="cep"]');
                if (!cepInput || !cepInput.value) {
                    e.preventDefault();
                    const span = form.querySelector('[data-bs-toggle="tooltip"]');
                    if (span) {
                        const tip = bootstrap.Tooltip.getInstance(span) || new bootstrap.Tooltip(span);
                        tip.show();
                        setTimeout(() => tip.hide(), 1500);
                    }
                    return;
                }

                form.dataset.sending = '1';

                const btn = form.querySelector('.finalize-btn');
                const spinner = btn.querySelector('.finalize-spinner');
                const text = btn.querySelector('.finalize-text');

                btn.disabled = true;
                btn.setAttribute('aria-busy', 'true');
                spinner.classList.remove('d-none');
                text.textContent = 'Processando pedido…';

            });
        });

        document.addEventListener('submit', async function(e) {
            if (e.target && e.target.id === 'apply-cupom-form') {
                e.preventDefault();
                const form = e.target;
                const btn = form.querySelector('button');
                const spinner = btn.querySelector('.spinner-border');
                const input = form.querySelector('input[name="codigo"]');
                const token = document.querySelector('meta[name="csrf-token"]').content;

                spinner.classList.remove('d-none');
                btn.disabled = true;

                try {
                    const res = await fetch("{{ route('carrinho.aplicar_cupom') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            codigo: input.value
                        })
                    });
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.error || 'Erro ao aplicar cupom');

                    document.querySelector('#cartCanvas .offcanvas-body').innerHTML = data.cart_html;
                } catch (err) {
                    alert(err.message || 'Erro ao aplicar cupom');
                } finally {
                    spinner.classList.add('d-none');
                    btn.disabled = false;
                }
            }
        });

        document.addEventListener('click', async function(e) {
            if (e.target && (e.target.id === 'remove-cupom-btn' || e.target.closest('#remove-cupom-btn'))) {
                e.preventDefault();
                const token = document.querySelector('meta[name="csrf-token"]').content;
                try {
                    const res = await fetch("{{ route('carrinho.remover_cupom') }}", {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        }
                    });
                    if (!res.ok) throw new Error('Erro ao remover cupom');
                    const data = await res.json();
                    document.querySelector('#cartCanvas .offcanvas-body').innerHTML = data.cart_html;
                } catch (err) {
                    alert('Não foi possível remover o cupom.');
                }
            }
        });
    </script>
@endpush
