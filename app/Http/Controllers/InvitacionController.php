<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Invitacion;
use Illuminate\Http\Request;

class InvitacionController extends Controller
{
    public function pendientes(Request $request)
    {
        // Aquí obtén las invitaciones pendientes desde tu base de datos
        $invitacionesPendientes = Auth::user()->invitacionesPendientes;

        // Marca las notificaciones como leídas
        Auth::user()->invitacionesPendientes->markAsRead();

        return view('diagramaSecuencia.notificacion', compact('invitacionesPendientes'));
    }

    public function enviarInvitacion(Request $request)
    {
        // Valida los datos del formulario
        $request->validate([
            'usuario' => 'required', // Asegúrate de tener las reglas de validación adecuadas
            'diagrama_id' => 'required|exists:diagramas,id',
        ]);

        // Obtén el usuario receptor por nombre de usuario o correo
        $usuarioReceptor = User::where('name', $request->input('usuario'))
            ->orWhere('email', $request->input('usuario'))
            ->first();

        if ($usuarioReceptor) {
            // Crea una nueva invitación
            $invitacion = Invitacion::create([
                'usuario_emisor' => auth()->user()->id,
                'usuario_receptor' => $usuarioReceptor->id,
                'diagrama_id' => $request->input('diagrama_id'),
                'aceptada' => false,
            ]);

            // Aquí puedes notificar al usuario receptor que tiene una nueva invitación,
            // ya sea mediante eventos en tiempo real, correo electrónico, etc.

            return response()->json(['success' => true, 'diagramaId' => $request->input('diagrama_id'), 'invitacion' => $invitacion]);
        }

        return response()->json(['success' => false]);
    }
}
