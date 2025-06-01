<!-- Botón visible solo en móvil -->
<div class="d-flex flex-column gap-0">
    <button class="rounded text-primary fs-6 fw-bold d-md-block d-md-none"  data-bs-toggle="collapse" data-bs-target="#mobileSidebar" aria-expanded="false" aria-controls="mobileSidebar">{{ __('Menú.') }}</button>

    <nav class="sidebar collapse d-md-block me-md-3" id="mobileSidebar">
        <div class="position-sticky pt-3">
            <h4 class="text-primary ps-3 d-none d-md-block">{{ __('Menú.') }}</h4> 
            <div class="list-group">
                <a class="nav-link list-group-item list-group-item-action {{ Str::endsWith(url()->current(), 'profile') ? 'active' : '' }}" href="/profile">{{ __('Perfil.') }}</a>
                <a class="nav-link list-group-item list-group-item-action {{ Str::contains(url()->current(), 'notifications') ? 'active' : '' }}" href="/profile/{{ $user->slug }}/notifications">{{ __('Notificaciones.') }}</a>
                <a class="nav-link list-group-item list-group-item-action {{ Str::contains(url()->current(), 'favorites') ? 'active' : '' }}" href="/profile/{{ $user->slug }}/favorites">{{ __('Favoritos.') }}</a>
                <a class="nav-link list-group-item list-group-item-action {{ Str::endsWith(url()->current(), '/history') ? 'active' : '' }}" href="/profile/{{ $user->slug }}/history">{{ __('Pedidos.') }}</a>
                @if(Auth::user()->role == 'seller')
                    <a class="nav-link list-group-item list-group-item-action {{ Str::contains(url()->current(), 'manage-products') ? 'active' : '' }}" href="/profile/{{ $user->slug }}/seller/manage-products">{{ __('GestionarProductos.') }}</a>
                    <a class="nav-link list-group-item list-group-item-action {{ Str::contains(url()->current(), 'sales-history') ? 'active' : '' }}" href="/profile/{{ $user->slug }}/seller/sales-history">{{ __('HistorialVentas.') }}</a>
                @elseif (Auth::user()->role == 'admin')
                    <a class="nav-link list-group-item list-group-item-action {{ Str::endsWith(url()->current(), 'admin') ? 'active' : '' }}" href="/admin">{{ __('ListaUsuarios.') }}</a>
                    <a class="nav-link list-group-item list-group-item-action {{ Str::contains(url()->current(), 'admin/products') ? 'active' : '' }}" href="/admin/products">{{ __('ListaProductos.') }}</a>
                    <a class="nav-link list-group-item list-group-item-action {{ Str::contains(url()->current(), 'admin/orders') ? 'active' : '' }}" href="/admin/orders">{{ __('ListaPedidos.') }}</a>
                @endif
                <a class="nav-link list-group-item list-group-item-action text-danger" href="/logout">{{ __('CerrarSesión.') }}</a>
            </div>
        </div>
    </nav>
</div>
