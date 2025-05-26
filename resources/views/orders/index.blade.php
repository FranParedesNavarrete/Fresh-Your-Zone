@extends('partials.layout')

@section('title', 'Resumen de pedido')

@section('content')
    <div class="container-fluid">
        <div class="container">
            <h2>Resumen del Pedido</h2>
            <p></p>
            <div class="purchase-summary">
                @component('components.table', ['columns' => ['imagenes', 'producto', 'descripción', 'estado', 'precio'], 'data' => $products])

                @endcomponent
                <div>
                    <h2>Método de Pago</h2>
                    <div class="d-flex flex-column">
                        <p>Nombre: {{ ucfirst($user->name) }}</p>
                        <p>Correo electrónico: {{ $user->email }}</p>

                            @if ($user->phone_number) 
                                <p id="phoneNumber">Teléfono: {{ $user->phone_number }} </p>
                            @else
                                <div class="d-flex">
                                    <label for="phone_number" class="form-label">Número de teléfono: </label> 
                                    <input type="tel" pattern="[0-9]{9}" id="phone_number" name="phone_number" class="form-control" value="{{ $user->phone_number }}" placeholder="Introduce tu número de teléfono" required>
                                </div>
                            @endif    

                        <div>
                            <label class="form-label"><strong>¿Cómo quieres recibir tu pedido?</strong></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="shipping_option" id="use_address" value="address" checked>
                                <label class="form-check-label" for="use_address">Usar mi dirección actual</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="shipping_option" id="use_deliveryPoint" value="point">
                                <label class="form-check-label" for="use_deliveryPoint">Elegir un punto de entrega</label>
                            </div>
                        </div>

                        <div id="addressZone" class="mt-3">
                            <label for="address" class="form-label">Dirección de envío:</label>
                            <input type="text" id="address" name="address" class="form-control" value="{{ $user->address }}" placeholder="No tienes una dirección guardada. Introduce tu dirección">
                        </div>

                        <div id="delivery_pointZone" class="mt-3 d-none">
                            <label for="delivery_point" class="form-label">Selecciona un punto de entrega:</label>
                            <select name="delivery_point" id="delivery_point" class="form-select">
                                <option value="" disabled>Selecciona uno...</option>
                                @foreach ($deliveryPoints as $point)
                                    <option value="{{ $point->address }}">{{ $point->name }} - {{ $point->address }} - {{ $point->availability }}</option>
                                @endforeach
                            </select>
                        </div>
                        <br>                        
                        <strong>Precio total:  {{ $totalPrice }}€</strong>
                    </div>
                    <div id="paypal-button-container" class="mt-4"></div>
                </div>
            </div>
            <div class="cancel-purchase">
                <button class="btn btn-danger">
                    <a href="{{ route('index') }}" class="text-decoration-none text-white">Cancelar</a>
                </button>
            </div>
        </div>
    </div>

    <script>
        const userId = @json($user->id);
        const cartProductIds = @json(session('cart_product_ids'));
        const totalPrice = @json($totalPrice);

        document.addEventListener('DOMContentLoaded', function() {
            validateAndRenderPayPal(userId, cartProductIds, totalPrice);
        });
    </script>

@endsection