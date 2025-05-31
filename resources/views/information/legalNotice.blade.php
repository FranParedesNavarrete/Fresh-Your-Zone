@extends('partials.layout')

@section('title', 'Aviso Legal')

@section('content')
    <div class="container-fluid">
        <div class="container w-75">
            <h2 class="text-center pb-2">{{ __('AvisoLegal.') }}</h2>

            <p>{{ __('AvisoLegalBienvenida.') }}.</p>

            <h5>{{ __('DatosTitularTitulo.') }}</h5>
            <p>
                {{ __('TitularNombre.') }}: Fresh Your Zone <br>
                {{ __('TitularEmail.') }}: freshyourzone@gmail.com <br>
                {{ __('TitularDomicilio.') }}: Calle Ficticia 123, Valencia, España
            </p>

            <h5>{{ __('PropiedadIntelectualTitulo.') }}</h5>
            <p>{{ __('PropiedadIntelectual.') }}</p>

            <h5>{{ __('ResponsabilidadTitulo.') }}</h5>
            <p>{{ __('Responsabilidad.') }}.</p>
        </div>
    </div>
@endsection