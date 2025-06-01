@extends('partials.layout')

@section('title', ucfirst($user->name))

@section('content')
    <div class="container-fluid">
        <div class="profile-list-filter">
            @include('partials.sidebar')
            <br>
            <div class="container">
                <h2>Notificaciones</h2>
                <p></p>
                @component('components.table', ['columns' => ['fecha', 'tipo', 'contenido'], 'data' => $notifications])

                @endcomponent
            </div>
        </div>
    </div>
@endsection