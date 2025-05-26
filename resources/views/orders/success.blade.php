@extends('partials.layout')

@section('content')
    <div class="container-fluid">
        <div class="container">
            <h1>¡Pago completado con éxito!</h1>
            <h2>¡Gracias por tu pedido, {{ auth()->user()->name }}!</h2>

            <ul>
                Productos:
                @foreach ($orders as $order)
                    <li class="ms-4 m-0 p-0">
                        {{ $order->product->name }}<br>
                    </li>
                @endforeach
                
                @php $firstOrder = $orders->first(); @endphp

                <li>Dirección: {{ $firstOrder->delivery_address }}</li>
                <li>Teléfono: {{ $firstOrder->delivery_phone }}</li>
                <li>Fecha: {{ \Carbon\Carbon::parse($firstOrder->date)->format('d/m/Y') }}</li>
            </ul>

            <p><strong>Total: {{ number_format($firstOrder->price, 2) }} €</strong></p>
            
            <a class="btn btn-primary" href="/">Volver al Inicio</a>
        </div>
    </div>
@endsection
