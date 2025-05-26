<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Http\Requests\ProductRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::query();

        if ($request->filled('search')) {
            $products->where('name', 'like', '%' . $request->input('search') . '%')
            ->orWhere('description', 'like', '%' . $request->input('search') . '%');
        }
        
        if ($request->filled('category')) {
            $products->where('category', $request->input('category'));
        }
        
        if ($request->filled('state')) {
            $products->where('state', $request->input('state'));
        }
        
        if ($request->filled('available')) {
            $products->where('stock', '>', 0);
        }

        if ($request->input('price')) {
            if (!Str::contains($request->input('price'), '-')) {
                $price = (float) $request->input('price');
                $products->whereBetween('price', [$price - 5, $price + 5]);
            }

            $priceRange = explode('-', $request->input('price'));
            if (count($priceRange) === 2) {
                $minPrice = (float)$priceRange[0];
                $maxPrice = (float)$priceRange[1];
                $products->whereBetween('price', [$minPrice, $maxPrice]);
            }
        }

        $products = $products->get();

        $favorites = [];

        // Si el usuario está autenticado, obtiene sus favoritos
        if(Auth::check()) {
            $favorites = DB::table('favorites')
            ->where('user_id', auth()->user()->id)
            ->pluck('product_id')
            ->toArray();
        }

        $categoriesDB = DB::selectOne("SHOW COLUMNS FROM products WHERE Field = 'category'")->Type; // Extraer todas las categorías de la tabla
        $statesDB = DB::selectOne("SHOW COLUMNS FROM products WHERE Field = 'state'")->Type; // Extraer todas los estados de la tabla

        preg_match('/^enum\((.*)\)$/', $categoriesDB, $matches); // Extraer el valor de la categoría
        preg_match('/^enum\((.*)\)$/', $statesDB, $matchesStates); // Extraer el valor del estado

        $categories = [];
        $states = [];

        // Si se encuentra una coincidencia, extraer las categorías y convertirlas en un array
        if (!empty($matches[1])) {
            $categories = array_map(function ($value) {
                return trim($value, "'");
            }, explode(',', $matches[1]));
        }

        // Si se encuentra una coincidencia, extraer los estados y convertirlos en un array
        if (!empty($matchesStates[1])) {
            $states = array_map(function ($value) {
                return trim($value, "'");
            }, explode(',', $matchesStates[1]));
        }

        return view('products.index', compact('products', 'favorites', 'request', 'categories', 'states')); 
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
    public function store(ProductRequest $request)
    {
        $user = Auth::user();
        $imageNames = [];

        // Guardar las imagens en la carpeta 'products' dentro de 'storage/app/public'
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = $image->store('products', 'public'); 
                $imageNames[] = $filename;
            }
        }

        // Guardar el producto en la base de datos
        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->category = $request->category;
        $product->stock = $request->stock;
        $product->state = $request->state;
        $product->images = implode('|', $imageNames); // Guardar las imágenes como una cadena de texto separada por '|'
        $product->seller_id = $user->id;
        $product->save();
    
        return redirect()->back()->with('success', 'Producto agregado con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('reviews'); // Carga las reseñas relacionadas con el producto

        $allCategories = DB::selectOne("SHOW COLUMNS FROM products WHERE Field = 'category'")->Type; // Extraer todas las categorías de la tabla
        $allStates = DB::selectOne("SHOW COLUMNS FROM products WHERE Field = 'state'")->Type;

        preg_match('/^enum\((.*)\)$/', $allCategories, $matches); // Extraer el valor de la categoría
        preg_match('/^enum\((.*)\)$/', $allStates, $matchesStates); // Extraer el valor del estado

        $categories = [];
        $states = [];

        // Si se encuentra una coincidencia, extraer las categorías y convertirlas en un array
        if (!empty($matches[1])) {
            $categories = array_map(function ($value) {
                return trim($value, "'");
            }, explode(',', $matches[1]));
        }
        // Si se encuentra una coincidencia, extraer los estados y convertirlos en un array
        if (!empty($matchesStates[1])) {
            $states = array_map(function ($value) {
                return trim($value, "'");
            }, explode(',', $matchesStates[1]));
        }

        return view('products.show', compact('product', 'categories', 'states'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        if ($request->hasFile('images')) {
            // Eliminar las imágenes antiguas de la carpeta 'products'
            $oldImages = explode('|', $product->images);
            foreach ($oldImages as $image) {
                Storage::disk('public')->delete($image);
            }

            // Guardar las nuevas imágenes en la carpeta 'products' y en la base de datos
            $imageNames = [];
            foreach ($request->file('images') as $image) {
                $filename = $image->store('products', 'public');
                $imageNames[] = $filename;
            }
            $product->images = implode('|', $imageNames); // Guardar las imágenes como una cadena de texto separada por '|'
        }

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'category' => $request->category,
            'stock' => $request->stock,
            'state' => $request->state,
        ]);

        return redirect()->back()->with('success', 'Producto actualizado con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Guarda en un array las imágenes del producto
        $imagenes = explode('|', $product->images);

        // Elimina todas las imágenes
        Storage::disk('public')->delete($imagenes);

        $product->delete();
        
        return redirect()->back()->with('success', 'Producto eliminado con éxito.');
    }

    // Función para mostrar los productos, categorias y favoritos en la página de inicio
    public function homeProducts() {
        $products = Product::all();
        $favorites = [];

        // Si el usuario está autenticado, obtiene sus favoritos
        if(Auth::check()) {
            $favorites = DB::table('favorites')
            ->where('user_id', auth()->user()->id)
            ->pluck('product_id')
            ->toArray();
        }

        $rawType = DB::selectOne("SHOW COLUMNS FROM products WHERE Field = 'category'")->Type; // Extraer todas las categorías de la tabla

        preg_match('/^enum\((.*)\)$/', $rawType, $matches); // Extraer el valor de la categoría
        $categories = [];

        // Si se encuentra una coincidencia, extraer las categorías y convertirlas en un array
        if (!empty($matches[1])) {
            $categories = array_map(function ($value) {
                return trim($value, "'");
            }, explode(',', $matches[1]));
        }

        $allCategories = collect($categories);
        $categories = $allCategories->shuffle()->take(4); // Solo coger 4 categorias aleatorias para el encabezado
        $categoriesIndex = $allCategories->diff($categories)->shuffle()->take(4); // Coge el resto de categorias para mostrar diferentes productos en la página de inicio

        return view('index', compact('products', 'categories', 'favorites', 'categoriesIndex'));
    }

    public function review(Request $request, Product $product)
    {
        $user = Auth::user();

        // Verificar si el usuario ya ha dejado una reseña para este producto
        if ($product->reviews()->where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'Ya has dejado una reseña para este producto.');
        }

        // Crear la reseña
        $review = DB::table('product_reviews')->insert([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'review' => $request->review,
            'date' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Reseña enviada con éxito.');
    }
}
