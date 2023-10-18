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

    public function guardarDiagrama(Request $request, DiagramaSecuencia $diagrama)
    {
        $diagrama->contenido = $request->input('contenido');
        $diagrama->save();
        return response()->json(['message' => 'Diagrama guardado con éxito']);
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
