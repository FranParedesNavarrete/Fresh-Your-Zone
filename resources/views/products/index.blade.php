@extends('partials.layout')

@section('title', 'Productos')

@section('content')
    <div class="container-fluid">
        <div class="container">
            @php 
                $categoryText = 'En FZY encontrarás una selección de artículos que cumplen con nuestros estándares de calidad y que han sido elegidos especialmente para ti. Explora nuestra colección de ropa '.ucfirst($request->input('category')).' y descubre lo mejor de cada categoría, todo en un solo lugar.';
            @endphp

            <div class="mb-3 border p-3 rounded shadow-sm mt-2">
                <div >
                    <div>
                        <h2>{{ $request->input('category') ? ucfirst($request->input('category')) : 'Productos' }}</h2> 
                    </div>
                    <small class="text-muted">{{ $categoryText}}</small>
                </div>
                <br>
            </div>
            <div class="products-list-filter">
                @include('partials.filters')

                <div class="product-list">
                    @forelse($products as $product)
                        @php 
                            if (auth()->check()) {
                                $isFavorite = auth()->user()->favoriteProducts()->where('product_id', $product->id)->exists();
                            } else {
                                $isFavorite = false;
                            }

                            $imagenPortada = $product->images ? explode('|', $product->images)[0] : null;
                        @endphp

                        <div class="card product-card position-relative">
                            <div class="position-absolute top-0 end-0 p-2 z-3">
                                <i id="favorite{{ $product->id }}" class="bi bi-heart{{ $isFavorite ? '-fill text-danger' : '' }} heartDarkmode" onclick="toggleFav(this, {{ $product->id }}, {{ auth()->check() ? 'true' : 'false' }})" title="Botón de favoritos"></i>
                            </div>

                            <a href="{{ route('products.show', $product->slug ) }}" class="text-decoration-none text-dark">
                                <img class="card-img-top" src="{{ $imagenPortada ? asset('storage/' . $imagenPortada) : asset('storage/products/product-image-default.jpg') }}" alt="Imagen principal del producto {{ $product->name }}">
                                <div class="card-body">
                                    <p class="card-text">{{ $product->name }}</p>
                                    <h5 class="card-title">{{ $product->price }} €</h5>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="container text-center">
                            @if ($request->input('search'))
                                <h5>No se encontraron resultados para "{{ $request->input('search') }}"</h5>
                            @else
                                <h5>No se encontraron productos en esta categoría</h5>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection