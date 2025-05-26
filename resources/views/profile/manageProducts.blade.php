@extends('partials.layout')

@section('title', ucfirst($user->name))

@section('content')
    <div class="container-fluid">
        <div class="d-flex">
            @include('partials.sidebar')

            <div class="container">
                <div class="d-flex justify-content-between">
                    <h2>Gestión de Productos</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newProductModal">Nuevo Producto</button>
                </div>
                <p></p>
                <div class="table-responsive" id="table">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr row-id="{{ $product->id }}">
                                    <td onclick="window.location='/products/{{ $product->slug }}'">{{ $product->name }}</td>
                                    <td onclick="window.location='/products/{{ $product->slug }}'">{{ $product->description }}</td>
                                    <td onclick="window.location='/products/{{ $product->slug }}'">{{ $product->price }} €</td>
                                    <td class="text-center">
                                        <i class="bi bi-pencil-fill text-warning" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->slug }}" title="Editar producto"></id>
                                        <i class="bi bi-x-lg text-danger" data-bs-toggle="modal" data-bs-target="#deleteProductModal{{ $product->slug }}" title="Eliminar producto"></i>
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

    <!-- Modal para crear un nuevo producto -->
    <div class="modal fade" id="newProductModal" tabindex="-1" aria-labelledby="newProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newProductModalLabel">Agregar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group pb-2">
                            <label for="name">Nombre del producto <span class="text-danger">*<span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-end pb-2">
                            <div class="form-group w-50 pb-2">
                                <label for="category">Categoría <span class="text-danger">*<span></label>
                                <select name="category" id="category" class="form-control" required>
                                    <option value="" {{ old('category') ? '' : 'selected' }} disabled>Seleccione una categoría</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>{{ ucfirst($category) }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group w-5 ps-2 pb-2">
                                <label for="price">Precio <span class="text-danger">*<span></label>
                                <input type="number" step="0.01" min="0" name="price" id="price" value="{{ old('price') }}" class="form-control" required>
                                @error('price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-end pb-2">
                            <div class="form-group w-50 pb-2">
                                <label for="state">Estado <span class="text-danger">*<span></label>
                                <select name="state" id="state" class="form-control" required>
                                    <option value="" {{ old('state') ? '' : 'selected' }} disabled>Estado del producto</option>
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
                                <input type="integer" min="0" name="stock" id="stock" value="{{ old('stock') }}" class="form-control" required>
                                @error('stock')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group pb-2">
                            <label for="description">Descripción <span class="text-danger">*<span></label>
                            <textarea maxlength="255" rows="3" name="description" id="description" class="form-control" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group pb-2">
                            <label for="images">Imagenes <span class="text-danger">*<span></label>
                            <input type="file" name="images[]" id="images" class="form-control" multiple required>
                            @error('images')
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

    @foreach ($products as $product)
        <!-- Modal para eliminar producto -->
        <div class="modal fade" id="deleteProductModal{{ $product->slug }}" tabindex="-1" aria-labelledby="newProductModalLabel{{ $product->slug }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newProductModalLabel{{ $product->slug }}">Eliminar Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('products.destroy', $product->slug ) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="name">¿Estás seguro de que deseas eliminar el producto {{ $product->name }}?</label>
                            </div>

                            <br>
                            <input type="submit" class="btn btn-danger" value="Eliminar">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Si hay errores vuelve  mostrar el modal indicando los errores -->
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = new bootstrap.Modal(document.getElementById('newProductModal'));
                modal.show();
            });
        </script>
    @endif
@endsection