<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Puesto;
use App\Models\Historial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\DocumentoRequerido;
use App\Models\ExpedienteDigital;

class EmpleadosController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $estado = $request->input('estado');
        $departamento = $request->input('departamento');

        $empleados = Empleado::with('puesto')
            ->when($q, function ($query) use ($q) {
                $query->where('nombre', 'like', '%'.$q.'%');
            })
            ->when($estado, function ($query) use ($estado) {
                $query->where('estado', $estado);
            })
            ->when($departamento, function ($query) use ($departamento) {
                $query->whereHas('puesto', function ($q) use ($departamento) {
                    $q->where('departamento', $departamento);
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(15);

        $puestos = Puesto::orderBy('nombre_puesto')->get();
        return view('empleados.index', compact('empleados', 'puestos'));
    }

    public function crear()
    {
        $puestos = Puesto::orderBy('nombre_puesto')->get();
        return view('empleados.crear', compact('puestos'));
    }

    public function guardar(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:128',
            'puesto_id' => 'nullable|integer|exists:puestos,id',
            'fecha_ingreso' => 'nullable|date',
            'estado' => 'required|string|max:32',
            'salario_diario' => 'nullable|numeric|min:0',
            'tipo_pago' => 'nullable|string|in:quincenal,mensual',
            'cuenta_bancaria_hash' => 'nullable|string|max:64',
            'fecha_ultimo_incremento' => 'nullable|date',
            'bloquear_pago' => 'nullable|boolean',
        ]);

        $empleado = Empleado::create([
            'nombre' => $data['nombre'],
            'puesto_id' => $data['puesto_id'] ?? null,
            'fecha_ingreso' => $data['fecha_ingreso'] ?? null,
            'estado' => $data['estado'],
            'salario_diario' => $data['salario_diario'] ?? null,
            'tipo_pago' => $data['tipo_pago'] ?? null,
            'cuenta_bancaria_hash' => isset($data['cuenta_bancaria_hash']) ? hash('sha256', $data['cuenta_bancaria_hash']) : null,
            'fecha_ultimo_incremento' => $data['fecha_ultimo_incremento'] ?? null,
            'bloquear_pago' => (bool) ($data['bloquear_pago'] ?? false),
        ]);
        Historial::create([
            'usuario_id' => $request->user()->id ?? null,
            'accion' => 'crear',
            'tabla' => 'empleados',
            'registro_id' => $empleado->id,
            'detalles' => json_encode($data),
        ]);

        return redirect()->route('empleados')->with('status', 'Empleado creado');
    }

    public function subirDocumento(Request $request, Empleado $empleado)
    {
        $data = $request->validate([
            'doc_id' => 'required|integer|exists:documentos_requeridos,id',
            'archivo' => 'required|file',
            'fecha_vencimiento' => 'nullable|date',
        ]);

        $path = $request->file('archivo')->store('expedientes', 'public');

        ExpedienteDigital::create([
            'empleado_id' => $empleado->id,
            'doc_id' => $data['doc_id'],
            'ruta_archivo' => $path,
            'fecha_subida' => now()->toDateString(),
            'fecha_vencimiento' => $data['fecha_vencimiento'] ?? null,
            'valido' => true,
            'usuario_id' => $request->user()->id ?? null,
        ]);

        Historial::create([
            'usuario_id' => $request->user()->id ?? null,
            'accion' => 'subir',
            'tabla' => 'expediente_digital',
            'registro_id' => $empleado->id,
            'detalles' => json_encode(['doc_id' => $data['doc_id'], 'ruta' => $path]),
        ]);

        return back()->with('status', 'Documento cargado');
    }
}
