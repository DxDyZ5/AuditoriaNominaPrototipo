<?php

namespace App\Http\Controllers;

use App\Models\DocumentoRequerido;
use App\Models\Historial;
use Illuminate\Http\Request;

class DocumentosController extends Controller
{
    public function index()
    {
        $documentos = DocumentoRequerido::orderBy('nombre_doc')->paginate(20);
        return view('documentos.index', compact('documentos'));
    }

    public function guardar(Request $request)
    {
        $data = $request->validate([
            'nombre_doc' => 'required|string|max:128',
            'es_obligatorio' => 'nullable|boolean',
            'requiere_vencimiento' => 'nullable|boolean',
            'dias_aviso' => 'nullable|integer|min:0',
        ]);

        $documento = DocumentoRequerido::create([
            'nombre_doc' => $data['nombre_doc'],
            'es_obligatorio' => $request->boolean('es_obligatorio'),
            'requiere_vencimiento' => $request->boolean('requiere_vencimiento'),
            'dias_aviso' => $data['dias_aviso'] ?? null,
        ]);

        Historial::create([
            'usuario_id' => $request->user()->id ?? null,
            'accion' => 'crear',
            'tabla' => 'documentos_requeridos',
            'registro_id' => $documento->id,
            'detalles' => json_encode($data),
        ]);

        return redirect()->route('documentos')->with('status', 'Documento registrado');
    }
}
