@extends('partials.layout')

@section('title', 'Panel de administración')
@section('content')
    <div class="container-fluid">
        <div class="profile-list-filter">
            @include('partials.sidebar')
            <br>
            <div class="container">
                <div class="d-flex justify-content-between">
                    <h2>Usuarios</h2>
                    <form action="{{ route('admin.index') }}" method="GET">
                        <select name="user_type" id="user_type" class="form-control" onchange="this.form.submit()">
                            <option value="" {{ request('user_type') == '' ? 'selected' : '' }}>Filtrar Rol</option>
                            <option value="buyer" {{ request('user_type') == 'buyer' ? 'selected' : '' }}>Compradores</option>
                            <option value="seller" {{ request('user_type') == 'seller' ? 'selected' : '' }}>Vendedores</option>
                            <option value="admin" {{ request('user_type') == 'admin' ? 'selected' : '' }}>Administradores</option>
                        </select>
                    </form>
                </div>
                <p></p>

                <div class="order-table">
                    <div class="table-responsive" id="table">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Teléfono</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    @php
                                        if ($user->role === 'seller') {
                                            $rol = 'Vendedor';
                                        } else if ($user->role === 'buyer') {
                                            $rol = 'Comprador';
                                        } else {
                                            $rol = 'Administrador';
                                        }
                                    @endphp

                                    <tr row-id="{{ $user->id }}">
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $rol }}</td>
                                        <td>{{ $user->phone_number }}</td>
                                        <td class="text-center">
                                            <i class="bi bi-person-fill-exclamation text-warning" data-bs-toggle="modal" data-bs-target="#sendNotificationModal{{ $user->slug }}" title="Enviar notificación"></i>
                                            <i class="bi bi-x-lg text-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->slug }}" title="Eliminar usuario"></i>
                                        </td>
                                    </tr>

                                    <!-- Modal para enviar notificación -->
                                    <div class="modal fade " id="sendNotificationModal{{ $user->slug }}" tabindex="-1" aria-labelledby="sendNotificationModalLabel{{ $user->slug }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="sendNotificationModalLabel{{ $user->slug }}">Enviar notificación a {{ $user->email }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.notifications.send') }}" method="POST">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                            <label for="subject" class="form-label">Asunto</label>
                                                            <textarea class="form-control" id="subject" name="subject" rows="3" required></textarea>
                                                            @error('subject')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Enviar</button>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal para eliminar un usuario -->
                                    <div class="modal fade " id="deleteUserModal{{ $user->slug }}" tabindex="-1" aria-labelledby="deleteUserModalLabel{{ $user->slug }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteUserModalLabel{{ $user->slug }}">¿Estas seguro de eliminar a {{ $user->email }}?</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.delete.user') }}" method="POST">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                            <p>Al eliminar a este usuario, se eliminarán todos los datos asociados a él, incluyendo su historial de pedidos, notificaciones y cualquier otra información relacionada. Esta acción no se puede deshacer.</p>
                                                        </div>
                                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Si hay errores vuelve  mostrar el modal indicando los errores -->
                                    @if ($errors->any())
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function () {
                                                const modal = new bootstrap.Modal(document.getElementById('sendNotificationModal{{ $user->slug }}'));
                                                modal.show();
                                            });
                                        </script>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No hay usuarios.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection