<!-- Botón visible solo en móvil -->
<div class="d-flex flex-column gap-0">
    <button class="rounded text-primary fs-6 fw-bold d-md-block d-md-none"  data-bs-toggle="collapse" data-bs-target="#mobileSidebar" aria-expanded="false" aria-controls="mobileSidebar">MENU</button>

    <nav class="sidebar collapse d-md-block" id="mobileSidebar">
        <div class="position-sticky pt-3">
            <h4 class="text-primary ps-3 d-none d-md-block">Menu</h4> 
            <div class="list-group">
                <a class="nav-link list-group-item list-group-item-action {{ Str::endsWith(url()->current(), 'profile') ? 'active' : '' }}" href="/profile">Perfil</a>
                <a class="nav-link list-group-item list-group-item-action {{ Str::contains(url()->current(), 'notifications') ? 'active' : '' }}" href="/profile/{{ $user->slug }}/notifications">Notificaciones</a>
                <a class="nav-link list-group-item list-group-item-action {{ Str::contains(url()->current(), 'favorites') ? 'active' : '' }}" href="/profile/{{ $user->slug }}/favorites">Favoritos</a>
                <a class="nav-link list-group-item list-group-item-action {{ Str::endsWith(url()->current(), '/history') ? 'active' : '' }}" href="/profile/{{ $user->slug }}/history">Historial</a>
                @if(Auth::user()->role == 'seller')
                    <a class="nav-link list-group-item list-group-item-action {{ Str::contains(url()->current(), 'manage-products') ? 'active' : '' }}" href="/profile/{{ $user->slug }}/seller/manage-products">Gestionar Productos</a>
                    <a class="nav-link list-group-item list-group-item-action {{ Str::contains(url()->current(), 'sales-history') ? 'active' : '' }}" href="/profile/{{ $user->slug }}/seller/sales-history">Historial de Ventas</a>
                @elseif (Auth::user()->role == 'admin')
                    <a class="nav-link list-group-item list-group-item-action {{ Str::endsWith(url()->current(), 'admin') ? 'active' : '' }}" href="/admin">Lista de Usuarios</a>
                    <a class="nav-link list-group-item list-group-item-action {{ Str::contains(url()->current(), 'admin/products') ? 'active' : '' }}" href="/admin/products">Lista de Productos</a>
                    <a class="nav-link list-group-item list-group-item-action {{ Str::contains(url()->current(), 'admin/orders') ? 'active' : '' }}" href="/admin/orders">Lista de Pedidos</a>
                @endif
                <a class="nav-link list-group-item list-group-item-action text-danger" href="/logout">Log Out</a>
            </div>
        </div>
    </nav>
</div>
