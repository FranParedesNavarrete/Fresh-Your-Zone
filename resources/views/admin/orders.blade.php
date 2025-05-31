@extends('partials.layout')

@section('title', 'Panel de administración')
@section('content')
    <div class="container-fluid">
    <div class="d-flex">
            @include('partials.sidebar')

            <div class="container">
                <h2>Pedidos</h2>
                <p></p>

                <div class="order-table">
                    <div class="table-responsive" id="table">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Producto</th>
                                    <th>Comprador</th>
                                    <th>Vendedor</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr>
                                        <td>{{ $order->date }}</td>
                                        <td onclick="window.location='/products/{{ $order->product->slug }}'">{{ $order->product->name }}</td>
                                        <td>{{ $order->buyer->email }}</td>
                                        <td>{{ $order->seller->email }}</td>
                                        <td>
                                            <select class="form-control" name="status" id="{{ $order->id }}" onchange="updateOrderStatus(this)">
                                                <option value="{{ $order->status }}">{{ ucfirst($order->status) }}</option>
                                                @foreach ($statuses as $status)
                                                    @if ($status != $order->status)
                                                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No hay pedidos.</td>
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