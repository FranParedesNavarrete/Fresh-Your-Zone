@extends('partials.layout')

@section('title', ucfirst($user->name))

@section('content')
    <div class="container-fluid">
        <div class="profile-list-filter">
            @include('partials.sidebar')
            <br>
            <div class="container">
                <h2>Historial de Ventas</h2>
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
                                @forelse ($sales as $sale)
                                    <tr row-id="{{ $sale->id }}">
                                        <td onclick="window.location='/products/{{ $sale->product->slug }}'">{{ $sale->product->created_at }}</td>
                                        <td onclick="window.location='/products/{{ $sale->product->slug }}'">{{ $sale->product->name }}</td>
                                        <td onclick="window.location='/products/{{ $sale->product->slug }}'">{{ $sale->product->price }} €</td>
                                        <td>
                                            @if ($sale->status == 'pedido')
                                                <select class="form-control" name="status" id="{{ $sale->id }}" onchange="updateOrderStatus(this)">
                                                        <option value="{{ $sale->status }}">{{ ucfirst($sale->status) }}</option>
                                                        <option value="enviado a FZY">Enviado a FZY</option>
                                                </select>
                                            @else
                                                {{ ucfirst($sale->status) }}
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