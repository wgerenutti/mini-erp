<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item mb-1">
                <a class="nav-link @if (request()->routeIs('dashboard')) active fw-bold @else text-dark @endif"
                    href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link @if (request()->routeIs('produtos.*')) active fw-bold @else text-dark @endif"
                    href="{{ route('produtos.index') }}">
                    <i class="bi bi-box-seam me-2"></i> Produtos
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link @if (request()->routeIs('pedidos.*')) active fw-bold @else text-dark @endif"
                    href="{{ route('pedidos.index') }}">
                    <i class="bi bi-cart4 me-2"></i> Pedidos
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link @if (request()->routeIs('cupons.*')) active fw-bold @else text-dark @endif"
                    href="{{ route('cupons.index') }}">
                    <i class="bi bi-ticket-perforated me-2"></i> Cupons
                </a>
            </li>
        </ul>
    </div>
</nav>
