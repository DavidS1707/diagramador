@extends('diagramaSecuencia.index') <!-- Asegúrate de que esto coincida con tu diseño de vista actual -->

@section('content')
    <div class="container">
        <h1>Notificaciones de Invitaciones Pendientes</h1>

        @if (count($invitacionesPendientes) > 0)
            <ul>
                @foreach ($invitacionesPendientes as $invitacion)
                    <li>{{ $invitacion->mensaje }}</li>
                    <!-- Agrega más detalles de la invitación según tu estructura de datos -->
                @endforeach
            </ul>
        @else
            <p>No tienes invitaciones pendientes en este momento.</p>
        @endif
    </div>
@endsection
