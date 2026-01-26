<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Puesto;
use App\Models\Historial;

class ImportacionController extends Controller
{
    public function empleados(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file',
        ]);

        $file = $request->file('archivo')->getRealPath();
        $handle = fopen($file, 'r');
        if (!$handle) return back()->with('status', 'No se pudo leer el archivo');

        $count = 0;
        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            if ($count == 0 && isset($row[0]) && mb_strtolower($row[0]) == 'nombre') {
                $count++;
                continue;
            }
            $nombre = $row[0] ?? null;
            $nombrePuesto = $row[1] ?? null;
            $departamento = $row[2] ?? null;
            $fechaIngreso = $row[3] ?? null;
            $estado = $row[4] ?? 'activo';

            if (!$nombre) continue;

            $puesto = null;
            if ($nombrePuesto) {
                $puesto = Puesto::firstOrCreate(
                    ['nombre_puesto' => $nombrePuesto, 'departamento' => $departamento]
                );
            }

            $empleado = Empleado::create([
                'nombre' => $nombre,
                'puesto_id' => $puesto ? $puesto->id : null,
                'fecha_ingreso' => $fechaIngreso ?: null,
                'estado' => $estado,
            ]);

            Historial::create([
                'usuario_id' => $request->user()->id ?? null,
                'accion' => 'importar',
                'tabla' => 'empleados',
                'registro_id' => $empleado->id,
                'detalles' => json_encode(['origen' => 'csv']),
            ]);

            $count++;
        }
        fclose($handle);

        return back()->with('status', 'Importación completa');
    }
}
