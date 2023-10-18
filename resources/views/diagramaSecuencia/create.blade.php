<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="The JointJS+ Kitchen Sink application serves as a template to help bring your idea to life in no time.">
    <title>Diagrama de Secuencia</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="{{ asset('package/rappid.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('Joint_Js/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('Joint_Js/css/theme-picker.css') }}">

    <!-- theme-specific application CSS  -->
    <link rel="stylesheet" type="text/css" href="{{ asset('Joint_Js/css/style.dark.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('Joint_Js/css/style.material.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('Joint_Js/css/style.modern.css') }}">
</head>

<body>

    <div id="app">
        <div class="app-header">
            <div class="app-title">
                <h1>Diagrama</h1>
            </div>
            <div class="toolbar-container"></div>
        </div>
        <div class="app-body">
            <div class="stencil-container"></div>
            <div class="paper-container"></div>
            <div class="inspector-container"></div>
            <div class="navigator-container"></div>
        </div>
    </div>

    <!-- JointJS+ dependencies: -->
    <script src="{{ asset('jquery/dist/jquery.js') }}"></script>
    <script src="{{ asset('lodash/lodash.js') }}"></script>
    <script src="{{ asset('backbone/backbone.js') }}"></script>
    <script src="{{ asset('graphlib/dist/graphlib.core.js') }}"></script>
    <script src="{{ asset('dagre/dist/dagre.core.js') }}"></script>

    <script src="{{ asset('package/rappid.js') }}"></script>

    <!--[if IE 9]>
        <script>
            // `-ms-user-select: none` doesn't work in IE9
            document.onselectstart = function() {
                return false;
            };
        </script>
    <![endif]-->

    <!-- Application files:  -->
    <script src="{{ asset('Joint_Js/js/config/halo.js') }}"></script>
    <script src="{{ asset('Joint_Js/js/config/selection.js') }}"></script>
    <script src="{{ asset('Joint_Js/js/config/inspector.js') }}"></script>
    <script src="{{ asset('Joint_Js/js/config/stencil.js') }}"></script>
    <script src="{{ asset('Joint_Js/js/config/toolbar.js') }}"></script>
    <script src="{{ asset('Joint_Js/js/config/sample-graphs.js') }}"></script>
    <script src="{{ asset('Joint_Js/js/views/main.js') }}"></script>
    <script src="{{ asset('Joint_Js/js/views/theme-picker.js') }}"></script>
    <script src="{{ asset('Joint_Js/js/models/joint.shapes.app.js') }}"></script>
    <script src="{{ asset('Joint_Js/js/views/navigator.js') }}"></script>

    <script>
        joint.setTheme('dark');
        app = new App.MainView({
            el: '#app'
        });
        themePicker = new App.ThemePicker({
            mainView: app
        });
        themePicker.render().$el.appendTo(document.body);
        //PARA MOSTRAR EL DIAGRAMA DE EJEMPLO
        window.addEventListener('load', function() {
            app.graph.fromJSON(@json($diagrama->contenido)); // Carga el diagrama desde PHP en formato JSON
        });
    </script>

    <!-- Local file warning: -->
    <div id="message-fs" style="display: none;">
        <p>The application was open locally using the file protocol. It is recommended to access it trough a <strong>Web
                server</strong>.</p>
        <p>Please see <a href="README.md">instructions</a>.</p>
    </div>
    <script>
        (function() {
            var fs = (document.location.protocol === 'file:');
            var ff = (navigator.userAgent.toLowerCase().indexOf('firefox') !== -1);
            if (fs && !ff) {
                (new joint.ui.Dialog({
                    width: 300,
                    type: 'alert',
                    title: 'Local File',
                    content: $('#message-fs').show()
                })).open();
            }
        })();
    </script>

</body>

</html>
