<?php

namespace App\Http\Controllers;

use App\Models\Hallazgo;
use Illuminate\Http\Request;

class HallazgosController extends Controller
{
    public function index()
    {
        $hallazgos = Hallazgo::orderBy('id', 'desc')->paginate(20);
        return view('hallazgos.index', compact('hallazgos'));
    }

    public function cerrar(Request $request, Hallazgo $hallazgo)
    {
        $hallazgo->estado = 'cerrado';
        $hallazgo->save();
        return back()->with('status', 'Hallazgo cerrado');
    }
}
