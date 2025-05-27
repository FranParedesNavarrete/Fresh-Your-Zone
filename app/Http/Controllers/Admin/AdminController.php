<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Order;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Verifica que el usuario autenticado sea un administrador
        if (auth()->user()->role != 'admin') {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        $user = auth()->user(); // Obtener el usuario autenticado

        $users = User::query();

        // Filtrar por tipo de usuario
        if ($request->input('user_type')) {
            $users = $users->where('role', $request->input('user_type'));
        }

        $users = $users->get();

        return view('admin.index', compact('users', 'user'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function productsIndex(Request $request)
    {
        // Verifica que el usuario autenticado sea un administrador
        if (auth()->user()->role != 'admin') {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        $user = auth()->user(); // Obtener el usuario autenticado

        $products = Product::query();

        // Filtrar por categoría de producto
        if ($request->input('category')) {
            $products = $products->where('category', $request->input('category'));
        }

        $products = $products->get();

        $categoriesDB = DB::selectOne("SHOW COLUMNS FROM products WHERE Field = 'category'")->Type; // Extraer todas las categorías de la tabla

        preg_match('/^enum\((.*)\)$/', $categoriesDB, $matches); // Extraer el valor de la categoría

        $categories = [];

        // Si se encuentra una coincidencia, extraer las categorías 
        if (!empty($matches[1])) {
            $categories = array_map(function ($value) {
                return trim($value, "'");
            }, explode(',', $matches[1]));
        }

        return view('admin.products', compact('products', 'user', 'categories'));
    }

    public function destroyProduct(Request $request)
    {
        // Verifica que el usuario autenticado sea un administrador
        if (auth()->user()->role != 'admin') {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        $product = Product::find($request->input('product_id')); // Buscar el producto a eliminar

        // Si lo encuentra separa las imágenes y las elimmina del servidor, le envia una notificación al vendedor y borra de la base de datos
        if ($product) {
            // Guarda en un array las imágenes del producto
            $imagenes = explode('|', $product->images);

            // Elimina todas las imágenes 
            Storage::disk('public')->delete($imagenes);


            // Se crea una notificación para el vendedor del producto eliminado
            $notification = new Notification();
            $notification->user_id = $product->seller_id;
            $notification->subject = 'El producto "' . $product->name . '" ha sido eliminado.';
            $notification->type = 'admin';
            $notification->date = now();
            $notification->save();

            // Elimina el producto de la base de datos
            $product->delete();

            return redirect()->back()->with('success', 'Producto eliminado correctamente.');
        } else {
            return redirect()->back()->with('error', 'Producto no encontrado.');
        }
    }

    public function ordersIndex()
    {
        // Verifica que el usuario autenticado sea un administrador
        if (auth()->user()->role != 'admin') {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        $user = auth()->user(); // Obtener el usuario autenticado

        $orders = Order::orderBy('created_at', 'desc')->get(); // Ordernar por fecha de creación

        $statusesDB = DB::selectOne("SHOW COLUMNS FROM orders WHERE Field = 'status'")->Type; // Extraer todas los estados de la tabla

        preg_match('/^enum\((.*)\)$/', $statusesDB, $matches); // Extraer el valor del estado

        $statuses = [];

        // Si se encuentra una coincidencia, extraer los estados 
        if (!empty($matches[1])) {
            $statuses = array_map(function ($value) {
                return trim($value, "'");
            }, explode(',', $matches[1]));
        }


        return view('admin.orders', compact('orders', 'user', 'statuses'));
    }

    public function updateOrderStatus(Request $request)
    {
        // Verifica que el usuario autenticado sea un administrador
        if (auth()->user()->role != 'admin') {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        $order = Order::find($request->input('order_id')); // Busca el pedido a modificar
    
        // Si no existe salta error junto con un mensaje
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pedido no encontrado.']);
        }
        
        // Si lo encuentra cambia el estado del pedido al seleccionado
        $order->fill(['status' => $request->input('status')]);
        $order->save(); 

        return response()->json(['success' => true, 'message' => 'Pedido actualizado.']);
    }
}
