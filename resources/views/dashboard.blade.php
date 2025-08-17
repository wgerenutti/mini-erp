@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">

            @include('partials.sidebar')

            {{-- Main content --}}
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>

                {{-- Metrics cards --}}
                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-box-seam display-4 text-primary"></i>
                                <h5 class="card-title mt-3">Total de Produtos</h5>
                                <h2 class="card-text display-6">{{ \App\Models\Produto::count() }}</h2>
                                <a href="{{ route('produtos.index') }}" class="btn btn-primary btn-sm mt-3">
                                    <i class="bi bi-eye me-1"></i> Ver Produtos
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-cart-check display-4 text-success"></i>
                                <h5 class="card-title mt-3">Total de Pedidos</h5>
                                <h2 class="card-text display-6">{{ \App\Models\Pedido::count() }}</h2>
                                <a href="{{ route('pedidos.index') }}" class="btn btn-success btn-sm mt-3">
                                    <i class="bi bi-eye me-1"></i> Ver Pedidos
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="bi bi bi-receipt display-4 text-info"></i>
                                <h5 class="card-title mt-3">Total de Cupons</h5>
                                <h2 class="card-text display-6">{{ \App\Models\Cupom::count() }}</h2>
                                <a href="{{ route('cupons.index') }}" class="btn btn-info btn-sm mt-3">
                                    <i class="bi bi-eye me-1"></i> Ver Cupons
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

        </div>
    </div>
@endsection
