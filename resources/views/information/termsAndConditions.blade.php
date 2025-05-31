@extends('partials.layout')

@section('title', 'Términos y Condiciones')

@section('content')
    <div class="container-fluid">
        <div class="container w-75">
            <h2 class="text-center pb-2">{{ __('Términos.') }}</h2>

            <p class="text-center">{{ __('TérminosBienvenido.') }}.</p>

            <h5>1. {{ __('UsoDelSitioTitulo.') }}</h5>
            <p>{{ __('UsoDelSitio.') }}.</p>

            <h5>2. {{ __('RegistroDeUsuariosTitulo.') }}</h5>
            <p>{{ __('RegistroDeUsuarios.') }}.</p>

            <h5>3. {{ __('PublicacionDeProductosTitulo.') }}</h5>
            <p>{{ __('PublicacionDeProductos.') }}.</p>

            <h5>4. {{ __('LimitacionDeResponsabilidadTitulo.') }}</h5>
            <p>{{ __('LimitacionDeResponsabilidad.') }}.</p>

            <h5>5. {{ __('ModificacionesTitulo.') }}</h5>
            <p>{{ __('Modificaciones.') }}.</p>
        </div>
    </div>
@endsection