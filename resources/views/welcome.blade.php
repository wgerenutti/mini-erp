<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mini ERP</title>

    {{-- CSS/JS via Vite --}}
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-light d-flex flex-column min-vh-100">

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">Mini ERP</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Entrar</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Registrar</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    {{-- Main --}}
    <main class="flex-fill d-flex align-items-center">
        <div class="container text-center py-5">
            <h1 class="display-4 mb-3">Bem‑vindo ao Mini ERP</h1>
            <p class="lead mb-4">
                Controle de Pedidos, Produtos, Cupons e Estoque de forma simples e eficiente.
            </p>
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg">Ir para o Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-success btn-lg me-2">Entrar</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">Registrar</a>
                @endif
            @endauth
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-white text-center py-3 shadow-sm mt-auto">
        <div class="container">
            <small class="text-muted">&copy; {{ date('Y') }} Mini ERP. Todos os direitos reservados.</small>
        </div>
    </footer>

</body>

</html>
