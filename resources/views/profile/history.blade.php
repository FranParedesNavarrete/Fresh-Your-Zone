@extends('partials.layout')

@section('title', ucfirst($user->name))

@section('content')
    <div class="container-fluid">
        <div class="profile-list-filter">
            @include('partials.sidebar')
            <br>
            <div class="profile-mobile-table">
                <div class="container">
                    <h2>Historial</h2>
                    <p></p>
                    @component('components.table', ['columns' => ['fecha', 'producto', 'precio', 'estado'], 'data' => $orders])

                    @endcomponent
                </div>
            </div>
        </div>
    </div>
@endsection