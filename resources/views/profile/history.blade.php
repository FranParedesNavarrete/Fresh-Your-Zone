@extends('partials.layout')

@section('title', ucfirst($user->name))

@section('content')
    <div class="container-fluid">
        <div class="profile-list-filter">
            @include('partials.sidebar')
            <br>
            <div class="container">
                <h2>Historial</h2>
                <p></p>
                <div class="profile-mobile-table">
                    <div class="table-responsive" id="table">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Descripción</th>
                                    <th>Precio</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr row-id="{{ $order->id }}">
                                        <td onclick="window.location='/products/{{ $order->product->slug }}'">{{ $order->product->created_at }}</td>
                                        <td onclick="window.location='/products/{{ $order->product->slug }}'">{{ $order->product->name }}</td>
                                        <td onclick="window.location='/products/{{ $order->product->slug }}'">{{ $order->product->price }} €</td>
                                        <td>
                                            @if ($order->status == 'enviado')
                                                <select class="form-control" name="status" id="{{ $order->id }}" onchange="updateOrderStatus(this)">
                                                        <option value="{{ $order->status }}">{{ ucfirst($order->status) }}</option>
                                                        <option value="recibido">Recibido</option>
                                                </select>
                                            @else
                                                {{ ucfirst($order->status) }}
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No tienes porductos disponibles.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection