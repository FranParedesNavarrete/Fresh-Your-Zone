@extends('partials.layout')

@section('title', 'Sobre Nosotros')

@section('content')
    <div class="container-fluid">
        <div class="container w-75">
            <h2 class="text-center pb-2">{{ __('SobreNosotros.') }}</h2>

            <p>{{ __('SobreNosotrosDescripcion.') }}.</p>

            <div class="d-flex justify-content-center">
                <img src="{{ asset('assets/images/logo/fzy-logo-dark.png') }}" alt="Logo FZY - Fresh Your Zone" class="logo-fzy w-25">
            </div>

            <p>{{ __('SobreNosotrosValores.') }}.</p>

            <p>{{ __('SobreNosotrosTecnologia.') }}</p>
        </div>
    </div>
@endsection