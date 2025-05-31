@extends('partials.layout')

@section('title', 'Política de Cookies')

@section('content')
    <div class="container-fluid">
        <div class="container w-75">
            <h2 class="text-center pb-2">{{ __('PolíticaCookies.') }}</h2 class="text-center">

            <p class="text-center">{{ __('PolíticaCookiesBienvenida.') }}.</p>

            <h5>1. {{ __('QueSonCookiesTitulo.') }}</h5>
            <p>{{ __('QueSonCookies.') }}.</p>

            <h5>2. {{ __('TiposCookiesTitulo.') }}</h5>
            <ul>
                <li><strong>{{ __('TiposCookiesEsencialTitulo.') }}:</strong> {{ __('TiposCookiesEsencial.') }}.</li>
                <li><strong>{{ __('TiposCookiesRendimientoTitulo.') }}:</strong> {{ __('TiposCookiesRendimiento.') }}.</li>
                <li><strong>{{ __('TiposCookiesPersonalizacionTitulo.') }}:</strong> {{ __('TiposCookiesPersonalizacion.') }}.</li>
            </ul>

            <h5>3. {{ __('GestionCookiesTitulo.') }}</h5>
            <p>{{ __('GestionCookies.') }}.</p>
        </div>
    </div>
@endsection