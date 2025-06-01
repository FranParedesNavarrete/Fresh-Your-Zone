@extends('partials.layout')

@section('title', 'Crear cuenta')

@section('content')
    <div class="container-fluid login">
        <div class="text-center mt-5">
            <br>
            <a class="navbar-brand" href="/" alt="Logo FZY"><img src="{{ asset('assets/images/logo/fzy-logo-dark.png') }}" alt="Logo FZY - Fresh Your Zone" class="logo-fzy"></a>
        </div>
        <div class="card ms-5 me-5 p-3">
            <h5 class="text-primary">{{ __('CrearCuenta.') }}</h5>
            <form action="{{ route('login.store') }}" method="POST">
                @csrf

                <div class="mb-3 mt-1">
                    <label for="name" class="form-label">{{ __('TitularNombre.') }} <span class="text-danger">*</label>
                    <input type="name" class="form-control" id="name" name="name" aria-describedby="nameError" placeholder="{{ __('NombrePlaceholder.') }}" value="{{ old('name') }}">
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('CorreoElectrónico.') }} <span class="text-danger">*</label>
                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailError" placeholder="{{ __('CorreoElectrónicoPlaceholder.') }}" value="{{ old('email') }}">
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Contraseña.') }} <span class="text-danger">*</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="{{ __('ContraseñaPlaceholder.') }}">
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">{{ __('Enviar.') }}</button>
            </form>
            <p></p>
            <a href="/login" >{{ __('IniciarSesión.') }}</a>
        </div>
    </div>
@endsection