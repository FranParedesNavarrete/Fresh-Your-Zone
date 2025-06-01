@extends('partials.layout')

@section('title', 'Inicia sesión')

@section('content')
    <div class="container-fluid login">
        <div class="text-center mt-5">
            <br>
            <a class="navbar-brand" href="/" alt="Logo FZY"><img src="{{ asset('assets/images/logo/fzy-logo-dark.png') }}" alt="Logo FZY - Fresh Your Zone" class="logo-fzy"></a>
        </div>
        <div class="card ms-5 me-5 mb- p-3">
            <h5 class="text-primary">{{ __('IniciarSesión.') }}</h5>
            <form action="{{ route('login.form') }}" method="POST">
                @csrf

                <div class="mb-3 mt-1">
                    <label for="email" class="form-label">{{ __('CorreoElectrónico.') }} <span class="text-danger">*</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="{{ __('CorreoElectrónicoPlaceholder.') }}" value="{{ old('email') }}">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Contraseña.') }} <span class="text-danger">*</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="{{ __('ContraseñaPlaceholder.') }}">
                    @if (session('error'))
                        <div class="text-danger"> {{ session('error') }} </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">{{ __('Enviar.') }}</button>
            </form>
            <p></p>
            <a href="/register" >{{ __('CrearCuenta.') }}</a>
        </div>
    </div>
@endsection