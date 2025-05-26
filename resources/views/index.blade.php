@extends('partials.layout')

@section('title', 'Inicio')

@section('content')
    <div class="navbar navbar-expand-lg navbar-category pb-2">
        <div class="container">
            @foreach($categories as $category)
                <a class="nav-link category-navbar" href="/products?category={{ $category }}">{{ ucfirst($category) }}</a>
            @endforeach
        </div>
    </div>

    <div class="container-fluid">
        @if (count($products) > 0)
            @foreach ($categoriesIndex as $categoryIndex)
                @php
                    $hasProducts = collect($products)->where('category', $categoryIndex)->isNotEmpty(); // Revisa si hay productos en la categoría
                @endphp
                <!-- Si no hay productos en la categoría, continúa con la siguiente iteración -->
                @if (!$hasProducts)
                    @continue
                @endif
                <div class="container">
                    <br>
                    <h5>{{ ucfirst($categoryIndex) }}</h5>
                    <div class="d-flex overflow-auto flex-nowrap" style="gap: 1rem;">
                        @foreach($products as $product)
                            @if ($product->category == $categoryIndex)
                                @php 
                                    if (auth()->check()) {
                                        $isFavorite = auth()->user()->favoriteProducts()->where('product_id', $product->id)->exists();
                                    } else {
                                        $isFavorite = false;
                                    }

                                    $imagenPortada = $product->images ? explode('|', $product->images)[0] : null;
                                @endphp

                                <div class="card product-card position-relative">
                                    <div class="position-absolute top-0 end-0 p-2" style="z-index: 10;">
                                        <i id="favorite{{ $product->id }}" class="bi bi-heart{{ $isFavorite ? '-fill text-danger' : '' }} heartDarkmode" onclick="toggleFav(this, {{ $product->id }}, {{ auth()->check() ? 'true' : 'false' }})" title="Botón de favoritos" style="cursor: pointer;"></i>
                                    </div>

                                    <a href="{{ route('products.show', $product->slug ) }}" class="text-decoration-none text-dark">
                                        <img class="card-img-top" src="{{ $imagenPortada ? asset('storage/' . $imagenPortada) : asset('storage/products/product-image-default.jpg') }}" alt="Imagen principal del producto {{ $product->name }}">
                                        <div class="card-body">
                                            <p class="card-text">{{ $product->name }}</p>
                                            <h5 class="card-title">{{ $product->price }} €</h5>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center mt-5">
                <h2>No hay productos disponibles</h2>
                <p>Hazte <a href="/profile">vendedor</a> y rompe el hielo.</p>
            </div>
        @endif
    </div>

    <script>
        window.favoritesFromServer = @json($favorites ?? []);
    </script>
@endsection