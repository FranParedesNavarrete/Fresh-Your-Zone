@extends('partials.layout')

@section('title', ucfirst($user->name))

@section('content')
    <div class="container-fluid">
        <div class="profile-list-filter">
            @include('partials.sidebar')

            <div class="container container-update">
                <br>
                <div class="update-container">
                    <form action="{{ route('profile.update', $user->slug) }}" method="POST" enctype="multipart/form-data" class="d-flex justify-content-around align-items-center form-update">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <br>
                            <div>
                                <label for="name">Nombre</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $user->name}}" required>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="email">Correo electrónico</label>
                                <input type="text" name="email" id="email" class="form-control" value="{{ $user->email}}">
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="address">Dirección</label>
                                <input type="text" name="address" id="address" class="form-control" value="{{ $user->address}}">
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="phone_number">Número de teléfono</label>
                                <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ $user->phone_number}}">
                                @error('phone_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <br>
                            <div class="text-center">
                                <input type="submit" class="btn btn-primary" value="Enviar">
                            </div>
                        </div>
                    </form>
                    <div class="change-avatar">
                        <img src="{{ asset('storage/' . ($user->avatar ?? 'avatars/default-avatar-icon.jpg')) }}" alt="Avatar" class="user-avatar">
                        <form action="{{ route('profile.change-avatar', $user->slug) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="avatar">Cambiar avatar</label>
                                <input type="file" name="avatar" id="avatar" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                                @error('avatar')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary mt-2">Actualizar Avatar</button>
                            </div>
                        </form>
                    </div>   
                </div>
            </div>
        </div>
    </div>
    <br>
@endsection