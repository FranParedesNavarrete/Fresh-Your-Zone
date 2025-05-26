<?php

namespace App\Http\Controllers\Favorite;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Verifica si el usuario está autenticado
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login'); // Redirige a la página de inicio de sesión si no está autenticado
        }

        $favorites = $user->favoriteProducts; // Obtiene los productos favoritos del usuario

        return view('favoriteS.index', compact('favorites'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($id)
    {
        // Crea el nuevo favorito en la base de datos
        Favorite::create([
            'user_id' => auth()->id(),
            'product_id' => $id
        ]);

        return response()->json(['success' => true, 'message' => 'Favorito agregado']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Favorite $favorite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Favorite $favorite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Favorite $favorite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($favoriteId)
    {
        // Busca el favorito en la base de datos y lo elimina        
        DB::table('favorites')
        ->where('user_id', auth()->id())
        ->where('product_id', $favoriteId)
        ->delete();

        return response()->json(['success' => true, 'message' => 'Favorito eliminado']);
    }
}
