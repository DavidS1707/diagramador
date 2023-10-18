<!doctype html>
<html lang="en">

<head>
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    <!-- Estilos CSS personalizados -->
    <link rel="stylesheet" href="{{ asset('assets/estilos.css') }}">

</head>

<body>
    <header>
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <h1>1er Examen Parcial SW1</h1>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Cerrar Sesión') }}
                                    </a>

                                    <a class="dropdown-item" href="{{ route('invitaciones') }}">
                                        <i class="bi bi-bell"></i> Ver invitaciones
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <div class="container">
            <h1>Diagramas de Secuencia</h1>
            <a href="#" class="btn btn-primary mb-3" data-bs-toggle="modal"
                data-bs-target="#crearDiagramaModal">Crear Nuevo Diagrama</a>
            @if ($diagramas->count())
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($diagramas as $diagrama)
                            <tr>
                                <td class="white-text">{{ $diagrama->nombre }}</td>
                                <td>
                                    <div style="display: flex ; justify-content: flex-end;">
                                        <a href="{{ route('diagrama/editar', $diagrama->nombre) }}"
                                            class="btn btn-primary">Editar</a>
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                            data-bs-target="#invitarModal{{ $diagrama->id }}">
                                            Invitar
                                        </button>
                                        <form method="POST" action="{{ route('diagramas.destroy', $diagrama) }}"
                                            onsubmit="return confirm('¿Estás seguro de que deseas eliminar este diagrama?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <!-- Modal de Invitación para cada diagrama -->
                            <div class="modal fade" id="invitarModal{{ $diagrama->id }}" tabindex="-1"
                                aria-labelledby="invitarModalLabel{{ $diagrama->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="invitarModalLabel{{ $diagrama->id }}"
                                                style="color: black;">Invitar usuario</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form wire:submit.prevent="enviarInvitacion" method="POST">
                                                @csrf
                                                <input type="hidden" name="diagrama_id" value="{{ $diagrama->id }}">
                                                <div class="form-group">
                                                    <label for="nombreUsuario{{ $diagrama->id }}">Nombre de Usuario o
                                                        Correo</label>
                                                    <input type="text" class="form-control" name="usuario"
                                                        id="nombreUsuario{{ $diagrama->id }}"
                                                        placeholder="Ingresa el nombre de usuario o correo" required>
                                                    <div class="invalid-feedback">El usuario no existe.</div>
                                                </div>
                                                <div class="alert alert-success mt-3"
                                                    id="confirmacionMensaje{{ $diagrama->id }}" style="display: none;">
                                                    Invitación enviada con éxito.
                                                </div>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary"
                                                    id="guardarDiagrama">Enviar invitación</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Script para las invitaciones -->
                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    const forms = document.querySelectorAll('form[id^="invitacionForm"]');

                                    forms.forEach(form => {
                                        form.addEventListener("submit", function(event) {
                                            event.preventDefault();
                                            const formData = new FormData(form);

                                            fetch('{{ route('enviar.invitacion') }}', {
                                                    method: 'POST',
                                                    body: formData,
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (data.success) {
                                                        // Restablece el formulario
                                                        form.reset();
                                                        // Muestra el mensaje de éxito
                                                        const confirmacionMensaje = document.querySelector(
                                                            `#confirmacionMensaje${data.diagramaId}`
                                                        );
                                                        confirmacionMensaje.style.display = "block";

                                                        // Notificar al usuario receptor en tiempo real
                                                        // Puedes usar AJAX o WebSocket para notificar al usuario receptor.
                                                        // Implementar esta notificación en tiempo real es un proceso complejo.

                                                        // Ejemplo de notificación simple usando una alerta:
                                                        alert(
                                                            `Has recibido una invitación para el diagrama ${data.invitacion.diagrama_id}`
                                                        );

                                                        // Otra opción es utilizar Laravel Echo o una biblioteca similar para notificaciones en tiempo real.
                                                    } else {
                                                        // Maneja errores si los hay
                                                        // Puedes mostrar un mensaje de error en el modal, por ejemplo
                                                        const errorMensaje = document.querySelector(
                                                            `#errorMensaje${data.diagramaId}`
                                                        );
                                                        errorMensaje.style.display = "block";
                                                    }
                                                })
                                                .catch(error => {
                                                    console.error('Error:', error);
                                                });
                                        });
                                    });
                                });
                            </script>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-table-message">
                    <p>Usted no tiene diagramas creados.</p>
                </div>
            @endif

            <h2>Diagramas como Invitado</h2>
            @if ($colaboraciones->count())
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($colaboraciones as $diagrama)
                            <tr>
                                <td>{{ $diagrama->nombre }}</td>
                                {{-- <td>
                                <a href="{{ route('diagramaSecuencia.show', $diagrama->id) }}"
                                    class="btn btn-primary">Ver</a>
                            </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-table-message">
                    <p>Usted no fue invitado a nigun diagrama.</p>
                </div>
            @endif

        </div>
    </main>

    <!-- Modal para crear un nuevo diagrama -->
    <div class="modal fade" id="crearDiagramaModal" tabindex="-1" aria-labelledby="crearDiagramaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crearDiagramaModalLabel" style="color: black;">Crear Nuevo Diagrama
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('diagramaSecuencia.index') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nombreDiagrama" class="form-label">Nombre del Diagrama</label>
                            <input type="text" class="form-control" name="nombre" id="nombreDiagrama"
                                placeholder="Ingrese el nombre del diagrama" required>
                            <!-- Mensaje de error para el nombre del diagrama -->
                            <div class="invalid-feedback">El nombre del diagrama es obligatorio o ya existe.</div>
                        </div>
                        <div class="mb-3">
                            <label for="descripcionDiagrama" class="form-label">Descripción (opcional)</label>
                            <textarea class="form-control" name="descripcion" id="descripcionDiagrama" rows="3"
                                placeholder="Ingrese una breve descripción del diagrama"></textarea>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="guardarDiagrama">Crear</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para validar el nombre del diagrama -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const nombreDiagramaInput = document.getElementById("nombreDiagrama");
            const crearDiagramaModal = new bootstrap.Modal(document.getElementById('crearDiagramaModal'));

            // Función para restablecer el estado del campo de nombre del diagrama
            function resetNombreDiagrama() {
                nombreDiagramaInput.classList.remove("is-invalid");
                nombreDiagramaInput.value = "";
            }

            const form = document.querySelector('form'); // Obtén el formulario

            form.addEventListener("submit", function(event) {
                event.preventDefault(); // Evita el envío normal del formulario

                if (nombreDiagramaInput.value.trim() === "") {
                    // El nombre del diagrama está vacío, muestra un mensaje de error
                    nombreDiagramaInput.classList.add("is-invalid");
                } else {
                    // El nombre del diagrama no está vacío, quita el mensaje de error
                    nombreDiagramaInput.classList.remove("is-invalid");

                    // Envía el formulario con AJAX
                    const formData = new FormData(form);

                    fetch(form.action, {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json()) // Si tu controlador devuelve JSON
                        .then(data => {
                            // Maneja la respuesta del controlador, como redirigir o mostrar un mensaje de éxito
                            if (data.success) {
                                // Restablece el estado del campo de nombre del diagrama
                                resetNombreDiagrama();
                                // Cierra la ventana modal
                                crearDiagramaModal.hide();
                                // Redirige o muestra un mensaje de éxito
                            } else {
                                // Maneja errores del controlador, si los hay
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }
            });
        });
    </script>

    <!-- LIBRERIAS Y ESTILOS -->
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Cargar Popper.js primero -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>

    <!-- Luego cargar Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
        integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
    </script>

    @livewireScripts
</body>

</html>
