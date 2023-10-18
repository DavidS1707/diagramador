<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Invitacion;
use App\Models\Colaborador;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class Invitaciones extends Component
{
    public $diagramaId;
    public $usuario;
    public $invitacionesPendientes;

    //Escuchar el evento de las notificaciones
    public function getListeners()
    {
        $user_id = auth()->user()->id;

        return [
            "echo-notification:App.Models.User{$user_id},notification" => 'render',
        ];
    }

    public function enviarInvitacion()
    {
        // Validar los datos aquí si es necesario

        // Obtén el ID del usuario receptor
        $usuarioReceptor = User::where('name', $this->usuario)->orWhere('email', $this->usuario)->first();

        if ($usuarioReceptor) {
            // Crea una nueva invitación
            $invitacion = Invitacion::create([
                'usuario_emisor' => auth()->user()->id,
                'usuario_receptor' => $usuarioReceptor->id,
                'diagrama_id' => $this->diagramaId,
                'aceptada' => false,
            ]);

            // Notifica al usuario receptor
            //$usuarioReceptor->notify(new \App\Notifications\NewInvitation($invitacion));
            Notification::send($usuarioReceptor, new \App\Notifications\NewInvitation($invitacion));

            // Muestra un mensaje de éxito o error si lo deseas
            session()->flash('message', 'Invitación enviada con éxito.');

            // Restablecer los campos del formulario
            $this->usuario = '';

            // Emitir un evento para actualizar la vista
            $this->emit('invitacionEnviada');
        } else {
            session()->flash('message', 'El usuario no existe.');
        }
    }

    public function loadInvitacionesPendientes()
    {
        $this->invitacionesPendientes = Invitacion::where('usuario_recibe_id', auth()->user()->id)
            ->where('aceptada', false)
            ->get();
    }

    public function aceptarInvitacion($invitacionId, $diagramaId)
    {
        // Actualiza la invitación a "aceptada" en la base de datos
        Invitacion::where('id', $invitacionId)->update(['aceptada' => true]);

        // Crea una nueva entrada en la tabla de colaboradores
        Colaborador::create([
            'usuario_id' => auth()->user()->id,
            'diagrama_id' => $diagramaId,
        ]);

        // Recarga las invitaciones pendientes después de aceptar una invitación
        $this->loadInvitacionesPendientes();
    }

    public function render()
    {
        $this->loadInvitacionesPendientes();

        return view('livewire.invitaciones', [
            'invitacionesPendientes' => $this->invitacionesPendientes,
        ]);
    }


    public function getUsersNotificationsProperty()
    {
        // Obtén las invitaciones pendientes del usuario actual
        $invitacionesPendientes = auth()->user()->invitacionesPendientes;

        return $invitacionesPendientes;
    }
}
