@extends('partials.layout')

@section('title', ucfirst($user->name))

@section('content')
    <div class="container-fluid">
        <div class="d-flex">
            @include('partials.sidebar')

            <div class="container">
                <h2>Notificaciones</h2>
                <p></p>
                @component('components.table', ['columns' => ['fecha', 'tipo', 'contenido'], 'data' => $notifications])

                @endcomponent
            </div>
        </div>
    </div>
@endsection