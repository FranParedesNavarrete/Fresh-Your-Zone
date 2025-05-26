@extends('partials.layout')

@section('title', 'Notificaciones')

@section('content')
    <div class="container-fluid">
        <div class="container">
            <h2>Notificaciones</h2>
            <p></p>
            @component('components.table', ['columns' => ['fecha', 'tipo', 'contenido'], 'data' => $notifications])

            @endcomponent
        </div>
    </div>
@endsection