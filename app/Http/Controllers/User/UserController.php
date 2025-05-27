<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use App\Models\Favorite;
use App\Models\Order;
use App\Models\Product;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obetner el usuario autenticado y pasarlo a la vista
        $userId = auth()->id();
        $user = User::find($userId);
        return view('profile.index', compact('user'));
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
    public function show(User $user)
    {
        // Verifica que el usuario sea el dueño del perfil
        if (auth()->user()->id != $user->id) {
            return redirect()->route('index')->with('error', 'Error no puedes acceder a este perfil');
        }

        return view('profile.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        // Verifica que el usuario sea el dueño del perfil
        if (auth()->user()->id != $user->id) {
            return redirect()->route('index')->with('error', 'Error no puedes acceder a este perfil');
        }


        // Verifica que los datos recibidos sean válidos para actualizar el perfil del usuario
        try {
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->address = $request->input('address');
            $user->phone_number = $request->input('phone_number');
            $user->save();
    
            return redirect()->route('profile.show', $user->slug)->with('success', 'Perfil actualizado con éxito');
    
        } catch (\Exception $e) {
            return redirect()->route('profile.index', $user->slug)->with('error', 'Error al actualizar el perfil: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // Verifica que el usuario autenticado sea un administrador
        if (auth()->user()->role != 'admin') {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        $user = User::find($request->input('user_id')); // Busca el usuario por su ID

        Storage::disk('public')->delete($user->avatar); // Elimina el avatar del usuario de la carpeta 'avatars'

        $user->delete(); // Elimina el usuario de la base de datos
        return redirect()->route('admin.index')->with('success', 'Usuario eliminado con éxito');
    }

    // Función para mostrar todas las notificaciones del usuario
    public function notifications(User $user)
    {
        // Verifica que el usuario sea el dueño del perfil
        if (auth()->user()->id != $user->id) {
            return redirect()->route('index')->with('error', 'Error no puedes acceder a este perfil');
        }

        $notifications = Notification::where('user_id', $user->id)->orderBy('updated_at', 'desc')->get(); // Obtener las notificaciones del usuario
        return view('profile.notifications', compact('user', 'notifications'));
    }

    // Función para guardar el nuevo avatar del usuario en el servidor y en la base de datos
    public function changeAvatar(Request $request, User $user) 
    {
        // Verifica que el usuario sea el dueño del perfil
        if (auth()->user()->id != $user->id) {
            return redirect()->route('index')->with('error', 'Error no puedes acceder a este perfil');
        }

        // Si no se ha recibido una imagen se redirige sin hacer cambios en el perfil de usuario
        if (!$request->hasFile('avatar')) {
            return redirect()->route('profile.index', $user->slug)->with('error', 'No se ha subido ninguna imagen.');
        }

        // Borra el avatar anterior si no es el por defecto
        if ($user->avatar && $user->avatar !== 'default-avatar-icon.jpg') {
            Storage::disk('public')->delete($user->avatar);
        }
    
        // Guarda el nuevo avatar
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
    
        $user->avatar = $avatarPath; // Guarda la ruta del nuevo avatar en la base de datos
        $user->save();
    
        return redirect()->route('profile.index', $user->slug)->with('success', 'Avatar actualizado con éxito');    
    }

    // Función para mostrar todos los productos marcados como favoritos del usuario 
    public function favorites(User $user)
    {
        // Verifica que el usuario sea el dueño del perfil
        if (auth()->user()->id != $user->id) {
            return redirect()->route('index')->with('error', 'Error no puedes acceder a este perfil');
        }

        $favorites = $user->favoriteProducts;; // Obtener los productos favoritos del usuario
        return view('profile.favorites', compact('user', 'favorites'));
    }

    // Función para cambiar la contraseña del usuario
    public function changePassword(UpdatePasswordRequest $request)
    {
        $user = auth()->user();

        // Verifica que la contraseña actual coincida
        if (!Hash::check($request->actual_password, $user->password)) {
            return back()->withErrors(['actual_password' => 'La contraseña actual no es correcta.']);
        }

        // Cambia la contraseña de la base de datos
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Contraseña actualizada correctamente.');
    }

    // Funcion para mostrar el historial de compras del usuario
    public function history(User $user) 
    {
        // Verifica que el usuario sea el dueño del perfil
        if (auth()->user()->id != $user->id) {
            return redirect()->route('index')->with('error', 'Error no puedes acceder a este perfil');
        }

        $orders = Order::where('buyer_id', $user->id)->orderBy('created_at', 'desc')->get(); // Obtener los 'pedidos' del usuario
        return view('profile.history', compact('user', 'orders'));
    }

    // Función para mostrar todas las ventas del usuario vendedor
    public function salesHistory(User $user)
    {
        // Verifica que el usuario sea el dueño del perfil
        if (auth()->user()->id != $user->id) {
            return redirect()->route('index')->with('error', 'Error no puedes acceder a este contenido');
        }

        // Verificar que el usuario tenga el rol de vendedor
        if ($user->role != 'seller') {
            return redirect()->back()->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        $sales = Order::where('seller_id', $user->id)->get(); // Obtener las ventas del vendedor
        return view('profile.salesHistory', compact('user', 'sales'));
    }

    // Función para mostrar todos los productos en venta del usuario vendedor
    public function manageProducts(User $user) 
    { 
        // Verifica que el usuario sea el dueño del perfil
        if (auth()->user()->id != $user->id) {
            return redirect()->route('index')->with('error', 'Error no puedes acceder a este contenido');
        }

        // Verificar que el usuario tenga el rol de vendedor
        if ($user->role != 'seller') {
            return redirect()->route('index')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        $products = Product::where('seller_id', $user->id)->get(); // Obtener los productos en venta del vendedor

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
        
        return view('profile.manageProducts', compact('user', 'products', 'categories', 'states'));
    }

    // Función para guardar el numero de teléfono mediante una petición AJAX
    public function savePhone(Request $request)
    {
        // Buscar el número de teléfono en la base de datos y si ya existe devolver un error
        $userExists = User::where('phone_number', $request->input('phone_number'))->first();
        if ($userExists) {
            return response()->json(['success' => false, 'message' => 'El número de teléfono ya está en uso.']);
        }

        // Si no existe se obtiene el usuario autenticado y se actualiza el número de teléfono
        $user = auth()->user();
        $user->phone_number = $request->input('phone_number');
        $user->save();

        return response()->json(['success' => true, 'message' => 'Número de teléfono guardado correctamente.']);
    }

    // Función para guardar la dirección mediante una petición AJAX
    public function saveAddress(Request $request)
    {
        // Obtener el usuario autenticado y actualizar la dirección
        $user = auth()->user();
        $user->address = $request->input('address');
        $user->save();

        return response()->json(['success' => true, 'message' => 'Dirección guardada correctamente.']);
    }

    // Para cambiar el rol del usuario a vendedor
    public function changeRole(Request $request)
    {
        // Verificar que el usuario esté autenticado
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'No tienes permiso para cambiar el rol.']);
        }

        // Cambia el rol a vendedor
        $user = auth()->user();
        $user->role = 'seller';
        $user->save();

        return response()->json(['success' => true, 'message' => 'Rol cambiado correctamente.']);
    }
}
