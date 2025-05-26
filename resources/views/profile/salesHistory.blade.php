@extends('partials.layout')

@section('title', ucfirst($user->name))

@section('content')
    <div class="container-fluid">
        <div class="d-flex">
            @include('partials.sidebar')

            <div class="container">
                <h2>Historial de Ventas</h2>
                <p></p>
                @component('components.table', ['columns' => ['fecha', 'producto', 'precio', 'estado'], 'data' => $sales])

                @endcomponent
            </div>
        </div>
    </div>
@endsection