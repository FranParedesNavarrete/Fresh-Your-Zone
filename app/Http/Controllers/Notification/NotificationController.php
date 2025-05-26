<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Requests\NotificationRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Verifica si el usuario está autenticado
        $userID = auth()->id();
        if (!$userID) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver tus notificaciones.');
        }

        $notifications = Notification::where('user_id', $userID)->orderBy('updated_at', 'desc')->get(); // Obtiene las notificaciones del usuario

        return view('notifications.index', compact('notifications'));
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
    public function store(NotificationRequest $request)
    {
        // Crea una nueva notificación
        $notification = new Notification();
        $notification->user_id = $request->input('user_id');
        $notification->subject = $request->input('subject');
        $notification->type = 'admin';
        $notification->date = now();
        $notification->save();

        return redirect()->back()->with('success', 'Notificación enviada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        //
    }
}
