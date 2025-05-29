@extends('partials.layout')

@section('title', 'Product')

@section('content')
    <div class="container-fluid">
        <div class="container">
            <h2>{{ ucfirst($product->name) }}</h2>
            <div class="product-info">
                <div id="carouselProduct{{ $product->id }}" class="carousel slide product-card-show mx-auto bg-white rounded" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @php
                            $imagenes = explode('|', $product->images);
                        @endphp

                        @foreach($imagenes as $index => $img)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $img) }}" class="d-block w-100 product-show" alt="Imagen {{ $index + 1 }} de {{ $product->name }}">
                            </div>
                        @endforeach
                    </div>

                    @if(count($imagenes) > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselProduct{{ $product->id }}" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselProduct{{ $product->id }}" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </button>
                    @endif
                </div>


                <div class="container d-flex flex-column w-50">
                    <h2>{{ $product->price }} €</h2>
                    <h4>{{ $product->description ? ucfirst($product->description) : 'Descripción no disponible.' }}</h4>
                    <h4>Estado: {{ ucfirst($product->state) }}</h4>
                    @if ($product->stock == 0)
                            <h4 class="text-danger">Sin stock</h4>
                        @else
                            @if ($product->stock < 5)
                                <h4 class="text-warning">Últimas unidades</h4>
                            @elseif ($product->stock > 5)
                                <h4 class="text-success">En stock</h4>
                            @endif

                            @if (auth()->check() && auth()->user()->role == 'seller' && auth()->user()->id == $product->seller_id)
                                <a class="btn btn-warning buy-btn" data-bs-toggle="modal" data-bs-target="#editProductModal">Editar</a>
                            @else
                                <div class="product-buttons">
                                    <a onclick="buyProducts({{ $product->id }})" class="btn btn-success buy-btn">Comprar</a>
                                    <a onclick="moveToShoppingCart({{ $product->id }}, {{ auth()->check() ? 'true' : 'false' }})" class="btn btn-primary cart-btn">Añadir al Carrito</a>
                                </div>
                            @endif
                        @endif
                </div>
            </div>
            <br>
            <h4>{{ $product->stock }} disponibles</h4>

            @if (auth()->check() && auth()->user()->canReview($product))
                <div class="container">
                    <div class="product-review mb-3 border p-3 rounded shadow-sm">
                        <h5>Deja una opinión</h5>
                        <form action="{{ route('products.review', $product->slug) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <textarea name="review" id="review" class="form-control" rows="3" required></textarea>
                                @error('review')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <input type="submit" value="Enviar opinión" class="btn btn-primary mt-2">
                        </form>
                    </div>
                </div>
            @endif

            @if ($product->reviews->isNotEmpty())
                <div class="container">
                    <br>
                    <h5>Opiniones de los clientes</h5>
                    @foreach ($product->reviews as $user)
                        <div class="product-review mb-3 border p-3 rounded shadow-sm">
                            <div class="d-flex justify-content-between pb-2">
                                <div>
                                    <img class="user-avatar comment-avatar" src="{{ asset('storage/'. ($user->avatar ?? 'avatars/default-avatar-icon.jpg')) }}" alt="Avatar de {{ $user->name }}">
                                    <strong>{{ ucfirst($user->name) }}</strong> 
                                    <br>
                                </div>
                                <small class="text-muted">Fecha: {{ \Carbon\Carbon::parse($user->pivot->date)->format('d/m/Y') }}</small>
                            </div>
                            <span>{{ $user->pivot->review }}</span> <br>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Modal para editar producto -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProductModalLabel">Editar Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('products.update', $product->slug) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group pb-2">
                                <label for="name">Nombre del producto <span class="text-danger">*<span></label>
                                <input type="text" name="name" id="name" value="{{ $product->name }}" class="form-control" required>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-end pb-2">
                                <div class="form-group w-50 pb-2">
                                    <label for="category">Categoría <span class="text-danger">*<span></label>
                                    <select name="category" id="category" class="form-control" required>
                                        <option value="{{ $product->category }}" {{ old('category') ? '' : 'selected' }}>{{ ucfirst($product->category) }}</option>
                                        @foreach($categories as $category)
                                            @if ($category != $product->category)
                                                <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>{{ ucfirst($category) }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group w-5 ps-2 pb-2">
                                    <label for="price">Precio <span class="text-danger">*<span></label>
                                    <input type="number" step="0.01" min="0" name="price" id="price" value="{{ $product->price }}" class="form-control" required>
                                    @error('price')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-end pb-2">
                                <div class="form-group w-50 pb-2">
                                    <label for="state">Estado <span class="text-danger">*<span></label>
                                    <select name="state" id="state" class="form-control" required>
                                        <option value="{{ $product->state }}" {{ old('state') ? '' : 'selected' }}>{{ ucfirst($product->state) }}</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state }}" {{ old('state') == $state ? 'selected' : '' }}>{{ ucfirst($state) }}</option>
                                        @endforeach
                                    </select>                     
                                    @error('state')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group w-5 ps-2 pb-2">
                                    <label for="stock">Stock <span class="text-danger">*<span></label>
                                    <input type="integer" min="0" name="stock" id="stock" value="{{ $product->stock }}" class="form-control" required>
                                    @error('stock')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group pb-2">
                                <label for="description">Descripción <span class="text-danger">*<span></label>
                                <textarea maxlength="255" rows="3" name="description" id="description" class="form-control" required>{{ $product->description }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="d-flex justify-content-between" for="images">Imagenes <i class="bi bi-info-circle-fill text-info" data-bs-toggle="tooltip" data-be-placement="top" title="Subir nuevas imagenes reemplará las anteriores."></i></label>
                                <input type="file" name="images[]" id="images" class="form-control" multiple>
                                @error('images')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                @error('images.*')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <br>
                            <input type="submit" class="btn btn-primary" value="Guardar">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
@endsection