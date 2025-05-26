@extends('partials.layout')

@section('title', 'Carrito')

@section('content')
    <div class="container-fluid">
        <div class="container">
            <h2>Carrito</h2>
            <p></p>
            <div class="table-responsive" id="table">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 1% !important;"></th>
                            <th>Producto</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $orderId = 0;
                        @endphp
                        @forelse ($orders as $order)
                            @php 
                                $orderId = $order->id;
                                $product = $order->product;
                                $productImage = $product->images ? explode('|', $product->images)[0] : null;
                            @endphp

                            <tr row-id="{{ $product->id }}">
                                <td><input type="checkbox" name="products[]" id="product_{{ $product->id }}" data-price="{{ $product->price }}" class="product-checked" value="{{ $product->id }}"></td>
                                <td onclick="window.location='/products/{{ $product->slug }}'">{{ $product->name }}</td>
                                <td onclick="window.location='/products/{{ $product->slug }}'">{{ $product->description }}</td>
                                <td onclick="window.location='/products/{{ $product->slug }}'">{{ $product->price }} €</td>
                                <td class="text-center"><i class="bi bi-x-lg text-danger" onclick="deleteFromCart({{ $product->id }})"></i></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No tienes productos en el carrito.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <br>
            <div class="d-flex justify-content-end">
                <p id="totalPrice">Total: 0</p> €
            </div>
            <div class="d-flex justify-content-end pt-2">
                <button class="btn btn-secondary" id="buyBtn" disabled onclick="buyProducts(null, {{ $orderId }})">Comprar</button>
            </div>
        </div>
    </div>
@endsection