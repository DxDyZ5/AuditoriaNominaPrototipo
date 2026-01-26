<?php

namespace App\Http\Controllers;

use App\Models\Puesto;
use App\Models\Historial;
use Illuminate\Http\Request;

class PuestosController extends Controller
{
    public function index()
    {
        $puestos = Puesto::orderBy('nombre_puesto')->paginate(20);
        return view('puestos.index', compact('puestos'));
    }

    public function guardar(Request $request)
    {
        $data = $request->validate([
            'nombre_puesto' => 'required|string|max:128',
            'departamento' => 'nullable|string|max:128',
        ]);

        $puesto = Puesto::create($data);

        Historial::create([
            'usuario_id' => $request->user()->id ?? null,
            'accion' => 'crear',
            'tabla' => 'puestos',
            'registro_id' => $puesto->id,
            'detalles' => json_encode($data),
        ]);

        return redirect()->route('puestos')->with('status', 'Puesto creado');
    }
}
