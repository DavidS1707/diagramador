<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\DiagramaSecuencia;
use App\Models\Colaborador;
use Illuminate\Http\Request;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\DiagramaSecuenciaRequest;

class DiagramaSecuenciaController extends Controller
{
    public function index()
    {
        $diagramas = DiagramaSecuencia::where('user_id', Auth::id())->get();
        $colaboraciones = Auth::user()->colaboraciones;

        return view('diagramaSecuencia.index', compact('diagramas', 'colaboraciones'));
    }

    public function create()
    {
        return view('diagramaSecuencia.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:diagrama_secuencias',
            'descripcion' => 'nullable',
        ]);

        $diagrama = new DiagramaSecuencia([
            'nombre' => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
            'contenido' => json_encode([]),
            'user_id' => Auth::id(),
        ]);

        $diagrama->save();

        return redirect()->route('diagramaSecuencia.index')->with('success', 'Diagrama de secuencia creado con éxito.');
    }

    public function show(DiagramaSecuencia $diagramaSecuencia)
    {
        //return view('diagramaSecuencia.show', compact('diagrama'));
    }

    public function mostrarDiagrama($id)
    {
        // Recupera el diagrama de la base de datos basado en el $id
        $diagrama = DiagramaSecuencia::find($id);

        if ($diagrama) {
            $contenido = json_encode($diagrama->contenido); // Codifica a JSON
            return view('diagrama.mostrar', compact('contenido'));
        } else {
            return redirect()->route('diagramaSecuencia.index')->with('error', 'Diagrama no encontrado.');
        }
    }



    public function invitar(Request $request)
    {
        // Validar los datos del formulario de invitación
        $request->validate([
            'usuario' => 'required',
            'diagrama_id' => 'required|exists:diagrama_secuencias,id',
        ]);

        // Lógica para enviar la invitación, puedes usar el sistema de notificaciones de Laravel aquí.

        // Redirigir de nuevo a la página anterior con un mensaje de éxito.
        return back()->with('success', 'Invitación enviada exitosamente.');
    }

    public function edit(DiagramaSecuencia $diagramaSecuencia)
    {
        // Verifica si el usuario tiene permiso para editar este diagrama, por ejemplo:
        if ($diagramaSecuencia->user_id != Auth::id()) {
            return abort(403); // Otra lógica de manejo de permisos
        }

        // Carga la vista de edición y pasa el diagrama como una variable compacta
        return view('layouts.diagrama', compact('diagramaSecuencia'));
    }

    public function update(DiagramaSecuenciaRequest $request, DiagramaSecuencia $diagramaSecuencia)
    {
        $diagramaSecuencia->update($request->all());
        return redirect()->route('diagrama-secuencia.index')->with('success', 'Diagrama de secuencia actualizado con éxito.');
    }

    public function guardarDiagrama(Request $request)
    {

        $diagrama = DiagramaSecuencia::find($request->input('diagramaId'));

        if (!$diagrama) {
            return redirect()->route('diagrama-secuencia.index')->with('error', 'Diagrama no encontrado.');
        }

        // $request->validate([
        //     'diagrama_json' => 'required|json', // Asegúrate de que el JSON sea válido
        // ]);

        // Actualiza el campo "contenido" en la base de datos para el diagrama con el ID dado
        $json = $request->input('diagrama_json');
        $diagrama->contenido = $json;
        $diagrama->save();

        return response()->json(['message' => 'Diagrama guardado con éxito']);
    }


    //GENERAR CODIGO EN JAVA, PYTHON Y JAVASCRIPT

    public function generarCodigo(Request $request)
    {
        dd("hola");
        $diagramaJson = $request->input('diagrama_json');

        // Llama a las funciones de generación de código para los lenguajes respectivos
        $codigoJava = $this->generarCodigoJavaDesdeJson($diagramaJson);
        $codigoPython = $this->generarCodigoPythonDesdeJson($diagramaJson);
        $codigoJavascript = $this->generarCodigoJavascriptDesdeJson($diagramaJson);

        return response()->json([
            'codigo_java' => $codigoJava,
            'codigo_python' => $codigoPython,
            'codigo_javascript' => $codigoJavascript,
        ]);
    }

    public function generarCodigoJavaDesdeJSON($diagramJson)
    {
        // Decodificar el JSON del diagrama en un array asociativo
        $diagramData = json_decode($diagramJson, true);

        // Inicializar el código Java
        $codigoJava = "public class Main {\n";
        $codigoJava .= "    public static void main(String[] args) {\n";

        // Recorrer los objetos del diagrama
        foreach ($diagramData['nodeDataArray'] as $objeto) {
            if (!$objeto['isGroup']) {
                // Agregar una clase Java para cada objeto en el diagrama
                $codigoJava .= "        " . "class " . $objeto['text'] . " {\n";

                // Recorrer las interacciones (mensajes)
                foreach ($diagramData['linkDataArray'] as $mensaje) {
                    if ($mensaje['from'] === $objeto['key']) {
                        $codigoJava .= "            " . "void " . $mensaje['text'] . "() {\n";
                        $codigoJava .= "                // Agregar lógica para la interacción aquí\n";
                        $codigoJava .= "            }\n";
                    }
                }

                $codigoJava .= "        " . "}\n";
            }
        }

        $codigoJava .= "    }\n";
        $codigoJava .= "}\n";

        return $codigoJava;
    }


    public function generarCodigoPythonDesdeJSON(Request $request)
    {
        $diagramJson = $request->input('diagrama_json');

        // Decodificar el JSON del diagrama en un array
        $diagramData = json_decode($diagramJson, true);

        $codigoPython = '';

        // Recorrer los objetos del diagrama
        foreach ($diagramData['nodeDataArray'] as $objeto) {
            if (!$objeto['isGroup']) {
                $codigoPython .= 'class ' . $objeto['text'] . ":\n";

                // Recorrer las interacciones (mensajes)
                foreach ($diagramData['linkDataArray'] as $mensaje) {
                    if ($mensaje['from'] == $objeto['key']) {
                        $codigoPython .= "    def " . $mensaje['text'] . "():\n";
                        $codigoPython .= "        # Agregar lógica para la interacción aquí\n";
                    }
                }
            }
        }

        return response()->json(['codigo_python' => $codigoPython]);
    }


    public function generarCodigoJavaScriptDesdeJSON(Request $request)
    {
        $diagramJson = $request->input('diagrama_json');

        // Decodificar el JSON del diagrama en un array
        $diagramData = json_decode($diagramJson, true);

        $codigoJavaScript = '';

        // Recorrer los objetos del diagrama
        foreach ($diagramData['nodeDataArray'] as $objeto) {
            if (!$objeto['isGroup']) {
                $codigoJavaScript .= 'class ' . $objeto['text'] . " {\n";

                // Recorrer las interacciones (mensajes)
                foreach ($diagramData['linkDataArray'] as $mensaje) {
                    if ($mensaje['from'] == $objeto['key']) {
                        $codigoJavaScript .= "    " . $mensaje['text'] . "() {\n";
                        $codigoJavaScript .= "        // Agregar lógica para la interacción aquí\n";
                        $codigoJavaScript .= "    }\n";
                    }
                }

                $codigoJavaScript .= "}\n";
            }
        }

        return response()->json(['codigo_javascript' => $codigoJavaScript]);
    }


    public function destroy(DiagramaSecuencia $diagramaSecuencia)
    {
        // Utiliza una transacción para asegurarte de que todas las eliminaciones ocurran o ninguna
        DB::beginTransaction();

        try {

            // Elimina los registros relacionados en la tabla colaborador
            $diagramaSecuencia->colaboradores()->delete();

            // Elimina los registros relacionados en la tabla invitaciones
            $diagramaSecuencia->invitaciones()->delete();

            // Elimina el diagrama
            $diagramaSecuencia->delete();

            // Confirma la transacción si todo se hizo correctamente
            DB::commit();

            return redirect()->route('diagramaSecuencia.index')
                ->with('success', 'Diagrama de secuencia eliminado con éxito.');
        } catch (\Exception $e) {
            // Revierte la transacción en caso de error
            DB::rollBack();

            return redirect()->route('diagramaSecuencia.index')->with('error', 'Error al eliminar el diagrama de secuencia.');
        }
    }
}
