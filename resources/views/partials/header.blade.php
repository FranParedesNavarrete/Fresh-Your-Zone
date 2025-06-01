@if(url()->current() != route('login') && url()->current() != route('register'))
    <nav id="navbarFZY" class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="/" alt="Logo FZY"><img src="{{ asset('assets/images/logo/fzy-logo-dark.png') }}" alt="Logo FZY - Fresh Your Zone" class="logo-fzy"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navHeader" aria-controls="navHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navHeader">
                <div>
                    <form class="d-flex" action="{{ route('products.index') }}" method="GET">
                        <input class="form-control me-2" id="searchInput" name="search" type="search" value="{{ request()->input('search') ?? ''}}" placeholder="Search" aria-label="Buscar...">
                        <button class="btn btn-outline-success" type="submit"><i class="bi bi-search" title="Botón de búsqueda"></i></button>
                    </form>
                </div>
                <div class="fs-6">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active position-relative pb-0" aria-current="page" href="/notifications">
                                <i class="bi {{ (auth()->check() && session('notificationsCount') > 0) ? 'bi-bell-fill' : 'bi-bell' }}" title="Botón de notificaciones"></i>
                                @if (auth()->check() && (session('notificationsCount') != 0))
                                    <span class="position-absolute top-75 start-75 count-icon translate-middle badge rounded-pill text-bg-danger">{{ session('notificationsCount') }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active pb-0" href="/favorites">
                                <i class="bi {{ (auth()->check() && (session('favoritesCount') > 0)) ? 'bi-heart-fill text-danger' : 'bi-heart favoriteBtn' }} " id="favoriteBtn" title="Botón de favoritos"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active position-relative pb-0" href="/orders/cart">
                                <i class="bi bi-cart4" id="shoppingCartIcon" title="Botón del carrito de compras"></i>
                                @if (auth()->check() && (session('cartProductsCount') != 0))
                                    <span class="position-absolute top-75 start-75 count-icon translate-middle badge rounded-pill text-bg-primary">{{ session('cartProductsCount') }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle pb-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-fill" title="Imagen de usuario default"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if (Auth::user()) 
                                    <li><a class="dropdown-item" href="/profile">{{ __('VerPerfil.') }}</a></li>
                                    @if (Auth::user()->role == 'admin')
                                        <li><a class="dropdown-item" href="/admin">{{ __('PanelAdmin.') }}</a></li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="/logout">{{ __('CerrarSesión.') }}</a></li>
                                @else
                                    <li><a class="dropdown-item" href="/login">{{ __('IniciarSesión.') }}</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="/register">{{ __('CrearCuenta.') }}</a></li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
@endif