@extends('partials.layout')

@section('title', 'Panel de administración')
@section('content')
    <div class="container-fluid">
    <div class="d-flex">
            @include('partials.sidebar')

            <div class="container">
                <div class="d-flex justify-content-between">
                    <h2>Productos</h2>
                    <form action="{{ route('admin.products.index') }}" method="GET">
                        <select name="category" id="category" class="form-control" onchange="this.form.submit()">
                            <option value="" {{ request('category') == '' ? 'selected' : '' }}>Filtrar Categoría</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ ucfirst($category) }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <p></p>

                <div class="table-responsive" id="table">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Vendedor</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr row-id="{{ $product->id }}">
                                    <td onclick="window.location='/products/{{ $product->slug }}'">{{ $product->name }}</td>
                                    <td onclick="window.location='/products/{{ $product->slug }}'">{{ $product->sellers->email }}</td>
                                    <td class="text-center">
                                        <i class="bi bi-person-fill-exclamation text-warning" data-bs-toggle="modal" data-bs-target="#sendNotificationModal{{ $product->slug }}" title="Enviar notificación sobre el producto {{ $product->name }}"></i>
                                        <i class="bi bi-x-lg text-danger" data-bs-toggle="modal" data-bs-target="#deleteProductModal{{ $product->slug }}" title="Eliminar producto {{ $product->name }}"></i>
                                    </td>
                                </tr>

                                <!-- Modal para enviar notificación acerca del producto -->
                                <div class="modal fade " id="sendNotificationModal{{ $product->slug }}" tabindex="-1" aria-labelledby="sendNotificationModalLabel{{ $product->slug }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="sendNotificationModalLabel{{ $product->slug }}">Enviar notificación sobre "{{ $product->name }}" a {{ $product->sellers->email }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('admin.notifications.send') }}" method="POST">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <input type="hidden" name="user_id" value="{{ $product->sellers->id }}">
                                                        <label for="subject" class="form-label">Asunto</label>
                                                        <textarea class="form-control" id="subject" name="subject" rows="3" required>Hola, te contactamos respecto al producto "{{ $product->name }}".</textarea>
                                                        @error('subject')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal para eliminar el producto -->
                                <div class="modal fade " id="deleteProductModal{{ $product->slug }}" tabindex="-1" aria-labelledby="deleteProductModalLabel{{ $product->slug }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteProductModalLabel{{ $product->slug }}">¿Estas seguro de eliminar el producto "{{ $product->name }}"?</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('admin.delete.product') }}" method="POST">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                        <label for="reason" class="form-label">Motivo de la eliminación</label>
                                                        <textarea class="form-control" id="reason" name="reason" rows="3" required>Hola, te contactamos para informarte que el producto "{{ $product->name }}" ha sido eliminado.</textarea>
                                                        @error('reason')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Si hay errores vuelve  mostrar el modal indicando los errores -->
                                @if ($errors->any())
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            const modal = new bootstrap.Modal(document.getElementById('sendNotificationModal{{ $user->slug }}'));
                                            modal.show();
                                        });
                                    </script>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No hay productos.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection