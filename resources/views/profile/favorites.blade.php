@extends('partials.layout')

@section('title', ucfirst($user->name))

@section('content')
    <div class="container-fluid">
        <div class="profile-list-filter">
            @include('partials.sidebar')
            <br>
            <div class="container">
                <h2>Favoritos</h2>
                <p></p>

                <div class="profile-mobile-table">
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
                                @forelse ($favorites as $product)
                                    <tr row-id="{{ $product->id }}">
                                        <td onclick="window.location='/products/{{ $product->slug }}'">{{ $product->name }}</td>
                                        <td onclick="window.location='/products/{{ $product->slug }}'">{{ $product->description }}</td>
                                        <td onclick="window.location='/products/{{ $product->slug }}'">{{ $product->price }} €</td>
                                        <td class="text-center"><i class="bi bi-x-lg text-danger" onclick="deleteFavorite({{ $product->id }})"></i></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No tienes favoritos guardados.</td>
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