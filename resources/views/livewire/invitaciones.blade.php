@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Notificaciones</h1>
        @if ($invitacionesPendientes->count() > 0)
            @foreach ($invitacionesPendientes as $invitacion)
                <p>Invitación de {{ $invitacion->nombreUsuario }} para {{ $invitacion->nombreDiagrama }}</p>
                <button
                    wire:click="aceptarInvitacion({{ $invitacion->id }}, {{ $invitacion->diagrama_id }})">Aceptar</button>
            @endforeach
        @else
            <p>No tiene invitaciones pendientes.</p>
        @endif
    </div>
@endsection
