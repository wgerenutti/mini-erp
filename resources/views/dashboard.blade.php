@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">

            {{-- Sidebar --}}
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item mb-1">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : 'text-dark' }}"
                                href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a class="nav-link {{ request()->routeIs('produtos.*') ? 'active fw-bold' : 'text-dark' }}"
                                href="{{ route('produtos.index') }}">
                                <i class="bi bi-box-seam me-2"></i>
                                Produtos
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a class="nav-link {{ request()->routeIs('pedido.*') ? 'active fw-bold' : 'text-dark' }}"
                                href="{{ route('pedido.index') }}">
                                <i class="bi bi-cart4 me-2"></i>
                                Pedidos
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a class="nav-link {{ request()->routeIs('cupons.*') ? 'active fw-bold' : 'text-dark' }}"
                                href="{{ route('cupons.index') }}">
                                <i class="bi bi-ticket-perforated me-2"></i>
                                Cupons
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a class="nav-link {{ request()->routeIs('estoque.*') ? 'active fw-bold' : 'text-dark' }}"
                                href="{{ route('estoque.index') }}">
                                <i class="bi bi-stack me-2"></i>
                                Estoque
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            {{-- Main content --}}
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>

                {{-- Metrics cards --}}
                <div class="row g-4">
                    <div class="col-sm-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title">Total de Produtos</h5>
                                <h2 class="card-text display-6">{{ \App\Models\Produto::count() }}</h2>
                                <a href="{{ route('produtos.index') }}" class="btn btn-primary btn-sm mt-3">Ver
                                    Produtos</a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

        </div>
    </div>
@endsection
