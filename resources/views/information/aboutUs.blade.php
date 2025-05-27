@extends('partials.layout')

@section('title', 'Sobre Nosotros')

@section('content')
    <div class="container-fluid">
        <div class="container w-75">
            <h2 class="text-center pb-2">Sobre Nosotros</h2>

            <p>Fresh Your Zone (FYZ) nace con la misión de ofrecer un espacio digital moderno, seguro y sostenible para la compraventa de ropa. Nuestra plataforma conecta a compradores y vendedores de toda España para dar una segunda vida a las prendas.</p>

            <div class="d-flex justify-content-center">
                <img src="{{ asset('assets/images/logo/fzy-logo-dark.png') }}" alt="Logo FZY - Fresh Your Zone" class="logo-fzy w-25">
            </div>

            <p>Creemos en la economía circular y en el poder de la moda como herramienta de expresión, sin renunciar a la responsabilidad ambiental. FYZ pone en tus manos la posibilidad de renovar tu armario de forma consciente y colaborar en una comunidad de usuarios comprometidos.</p>

            <p>Desarrollada con Laravel, FYZ ofrece un entorno funcional, seguro y atractivo, pensado para facilitar la experiencia del usuario. ¡Únete a la revolución del estilo sostenible!</p>
        </div>
    </div>
@endsection