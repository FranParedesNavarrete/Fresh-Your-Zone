@extends('partials.layout')

@section('title', 'Política de Privacidad')

@section('content')
    <div class="container-fluid">
        <div class="container w-75">
            <h2 class="text-center pb-2">{{ __('PolíticaPriv.') }}</h2>

            <p class="text-center">{{ __('PolíticaPrivBienvenida.') }}.</p>

            <h5>1. {{ __('InformaciónRecopiladaTitulo.') }}</h5>
            <p>{{ __('InformaciónRecopilada.') }}.</p>

            <h5>2. {{ __('UsoInformacionTitulo.') }}</h5>
            <p>{{ __('UsoInformacion.') }}.</p>

            <h5>3. {{ __('CookiesTitulo.') }}</h5>
            <p>{{ __('Cookies.') }}.</p>

            <h5>4. {{ __('SeguridadTitulo.') }}</h5>
            <p>{{ __('Seguridad.') }}.</p>
        </div>
    </div>
@endsection