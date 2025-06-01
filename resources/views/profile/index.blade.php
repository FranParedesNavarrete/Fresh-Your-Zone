@extends('partials.layout')

@section('title', ucfirst($user->name))

@section('content')
    <div class="container-fluid">
        <div class="profile-list-filter">
            @include('partials.sidebar')

            <div class="container container-update">
                <div class="user-info">
                    <ul>
                        <li>{{ ucfirst($user->name) }}</li>
                        <li>{{ $user->email }}</li>
                        <li>{{ $user->address ? $user->address : 'No hay dirección asignada' }}</li>
                        <li>{{ $user->phone_number ? $user->phone_number : ''}}</li>
                        <br>
                        <li>
                            <div class="d-flex gap-2">
                                <a href="{{ route('profile.show', $user->slug) }}" class="btn btn-primary">Editar perfil</a>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Cambiar contraseña</button>
                            </div>
                        </li>
                        <br>
                        @if(Auth::user()->role != 'seller' && Auth::user()->role != 'admin')
                            <a class="btn btn-success" onclick="changeRoleToSeller()">Empezar a vender</a>
                        @endif
                    </ul>
                    <div>
                        <img src="{{ asset('storage/' . ($user->avatar ?? 'avatars/default-avatar-icon.jpg')) }}" alt="Avatar" class="user-avatar">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Cambiar contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('profile.change-password') }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="actual_password">Contraseña actual</label>
                        <input type="password" name="actual_password" id="actual_password" class="form-control" required>
                        @error('actual_password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="new_password">Nueva contraseña</label>
                        <input type="password" name="new_password" id="new_password" class="form-control" required>
                        @error('new_password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="repeat_password">Repite contraseña</label>
                        <input type="password" name="repeat_password" id="repeat_password" class="form-control" required>
                        @error('repeat_password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <br>
                    <input type="submit" class="btn btn-primary" value="Guardar">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
            </div>
        </div>
    </div>
@endsection