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
