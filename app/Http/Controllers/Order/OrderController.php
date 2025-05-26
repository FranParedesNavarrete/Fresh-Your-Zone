<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\DeliveryPoint;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener los productos del carrito de la sesión, el usuario autenticado y los puntos de entrega
        $ids = session('cart_product_ids', []);
        $user = Auth::user();
        $deliveryPoints = DeliveryPoint::all();

        // Si no hay productos en el carrito, redirige al inicio
        if (empty($ids)) {
            $products = Product::all();

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
    

            return view('index', compact('products', 'categories', 'categoriesIndex', 'user', 'deliveryPoints'));
        }

        // Obtener los productos del carrito y calcular el precio total
        $products = Product::whereIn('id', $ids)->get();

        $totalPrice = DB::table('products')
        ->whereIn('id', $ids)
        ->sum('price');
    
        return view('orders.index', compact('products', 'totalPrice', 'user', 'deliveryPoints'));
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
        $buyerId = auth()->id();
        $productIds = $request->input('product_id');
        $price = $request->input('price');
        $address = $request->input('delivery_address');
        $phone = preg_replace('/^Teléfono:\s*/', '', $request->input('delivery_phone')); // Limpia si viene con prefijo
    
        // Verifica que hayan productos en el carrito
        if (empty($productIds)) {
            return response()->json(['success' => false, 'message' => 'Carrito vacío']);
        }

        $orderIds = [];

        foreach ($productIds as $productId) {
            $product = Product::findOrFail($productId);
    
            // Buscar un pedido ya existente en estado 'carrito'
            $order = Order::where('product_id', $productId)
                ->where('buyer_id', $buyerId)
                ->where('seller_id', $product->seller_id)
                ->where('status', 'carrito')
                ->first();
    
            if ($order) {
                $order->update([
                    'status' => 'pedido',
                    'date' => Carbon::today(),
                    'price' => $price,
                    'delivery_address' => $address,
                    'delivery_phone' => $phone,
                ]);

                $orderIds[] = $order->id; // Guardar el id del pedido
            } else {
                $order = Order::create([
                    'seller_id' => $product->seller_id,
                    'product_id' => $productId,
                    'buyer_id' => $buyerId,
                    'status' => 'pedido',
                    'date' => Carbon::today(),
                    'price' => $price,
                    'delivery_address' => $address,
                    'delivery_phone' => $phone,
                    'created_at' => Carbon::now(),
                ]);
            }
    
            $orderIds[] = $order->id; // Guardar el id del pedido
        }
    
    
        // Limpia el carrito después del pago
        session()->forget('cart_product_ids');
        session(['last_orders' => $orderIds]); // Guardamos los pedidos en la sesión para mostrarlos en la vista de éxito
    
        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $product)
    {
        //
    }

    public function shoppingCart() {
        // Obtener los productos del carrito de la base de datos
        $orders = Order::with('product')
        ->where('buyer_id', auth()->id())
        ->where('status', 'carrito')
        ->get();

        return view('orders.cart', compact('orders'));
    }

    public function moveToShoppingCart(Request $request) {
        // Obtener el producto con el id recibido, el id del vendedor y el id del comprador
        $product = Product::findOrFail($request->input('id'));
        $sellerId = $product->seller_id;
        $buyerId = auth()->id();

        $order = null;
        
        if ($request->input('order_id')) {
            // Verificar si ya existe la orden en la base de datos
            $order = Order::find($request->input('order_id'));
        }

        if ($order) {
            // Si ya existe, actualiza el estado a 'carrito'
            $order->update([
                'status' => 'carrito',
                'date' => Carbon::today(),
            ]);
        } else {
            // Si no existe, crea una nueva orden
            Order::create([
                'product_id' => $product->id,
                'buyer_id' => $buyerId,
                'seller_id' => $sellerId,
                'status' => 'carrito',
                'date' => Carbon::today(),
                'created_at' => Carbon::now(),
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Producto guardado en el carrito.']);
    }

    public function removeFromShoppingCart(Request $request) 
    {
        // Obtener el producto con el id recibido, el id del vendedor y el id del comprador
        $product = Product::findOrFail($request->input('id'));
        $sellerId = $product->seller_id;
        $buyerId = auth()->id();

        // Obtener el producto del carrito de la base de datos y eliminarlo
        Order::where('seller_id', $sellerId)
        ->where('buyer_id', $buyerId)
        ->where('product_id', $request->input('id'))
        ->where('status', 'carrito')
        ->delete();

        return response()->json(['success' => true, 'message' => 'Producto eliminado del carrito']);
    }

    public function prepareProducts(Request $request) 
    {
        // Obtener el id o los ids de los productos del carrito
        $ids = $request->input('id');

        // Si se recibe un solo id se convierte a un array
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        session(['cart_product_ids' => $ids]); // Guardar los ids de los productos en la sesión

        return response()->json(['success' => true, 'message' => 'Producto eliminado del carrito']);
    }

    public function success() 
    {
        $orderIds = session('last_orders', []); // Obtener los pedidos de la sesión

        // Si esta vacío, redirige al inicio
        if (empty($orderIds)) {
            return redirect('/')->with('error', 'No se encontraron órdenes recientes');
        }

        $orders = Order::with('product')
        ->whereIn('id', $orderIds)
        ->where('status', 'pedido')
        ->get();

        return view('orders.success', compact('orders'));
    }
}
