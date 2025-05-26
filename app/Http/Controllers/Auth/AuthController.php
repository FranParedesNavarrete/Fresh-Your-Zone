<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth.login'); // Vista de inicio de sesión
    }

    public function registerIndex() 
    {
        return view('auth.register');
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
    public function store(UserRequest $request)
    {
        // Validar el formulario de registro y guarda el usuario
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->get('password'));
        $user->role = 'buyer';
        $user->save();

        Auth::login($user); // Iniciar sesión automáticamente después del registro

        return redirect()->route('index');
    }

    public function login(Request $request)
    {
        // Validar el formulario de inicio de sesión, solo se requiere email y password
        $credentials = $request->only('email', 'password');

        // Verificar las credenciales del usuario, si son correctas inicia sesión, sino redirige de nuevo al formulario
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('index');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al iniciar sesión');
        }
    }

    public function logout(Request $request)
    {
        // Cerrar sesión del usuario y redirigir a la página de inicio
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
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
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
