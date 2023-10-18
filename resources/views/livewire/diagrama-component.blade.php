@extends('layouts.diagrama')

@section('content')
    <div class="paper-container">
        @if (isset($diagrama))
            <script>
                var diagramContent = @json($diagrama->contenido);

                // Función para cargar el diagrama desde la base de datos
                function cargarDiagramaDesdeBD() {
                    // Parsea el contenido JSON y crea el diagrama
                    var diagram = new joint.shapes.app.Diagram(JSON.parse(diagramContent));
                    return diagram;
                }

                // Inicializa el diagrama con el contenido recuperado
                var diagram = cargarDiagramaDesdeBD();
                var paper = new joint.dia.Paper({
                    el: $('.paper-container'),
                    model: diagram.graph,
                    width: 800,
                    height: 600,
                    gridSize: 10,
                    drawGrid: true,
                });

                // Establece un intervalo para actualizar el diagrama automáticamente cada X segundos (por ejemplo, cada 30 segundos)
                setInterval(function() {
                    guardarDiagramaEnBD(diagram);
                }, 2000);

                // Función para guardar el diagrama en la base de datos
                function guardarDiagramaEnBD(diagram) {
                    // Obtiene el contenido actual del diagrama en formato JSON
                    var diagramContent = JSON.stringify(diagram.toJSON());

                    // Envía una solicitud AJAX para guardar el contenido
                    $.ajax({
                        url: '{{ route('guardar-diagrama', $diagrama) }}',
                        type: 'POST',
                        data: {
                            contenido: diagramContent,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log('Diagrama guardado con éxito');
                        },
                        error: function(error) {
                            console.error('Error al guardar el diagrama');
                        }
                    });
                }
            </script>
        @endif
    </div>
@endsection
