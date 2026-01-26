@extends('layouts.app')

@section('site_title', formatTitle(['Documentos requeridos', config('settings.title')]))

@section('content')
<div class="bg-base-1 flex-fill">
    <div class="container py-3 my-3">
        @include('shared.breadcrumbs', ['breadcrumbs' => [
            ['url' => route('dashboard'), 'title' => 'Inicio'],
            ['title' => 'Documentos requeridos'],
        ]])

        <div class="d-flex align-items-end">
            <h1 class="h2 mb-3 flex-grow-1 text-truncate">Documentos requeridos</h1>
        </div>

        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header">
                <div class="row">
                    <div class="col"><div class="font-weight-medium py-1">Nuevo documento</div></div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('documentos.guardar') }}">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <input type="text" name="nombre_doc" class="form-control" placeholder="Nombre del documento" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="es_obligatorio" id="es_obligatorio">
                                <label class="form-check-label" for="es_obligatorio">Obligatorio</label>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="requiere_vencimiento" id="requiere_vencimiento">
                                <label class="form-check-label" for="requiere_vencimiento">Tiene vencimiento</label>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <input type="number" name="dias_aviso" class="form-control" placeholder="Días aviso">
                        </div>
                        <div class="col-md-2 mb-3">
                            <button class="btn btn-primary">Agregar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header">
                <div class="row">
                    <div class="col"><div class="font-weight-medium py-1">Listado</div></div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Obligatorio</th>
                                <th>Vencimiento</th>
                                <th>Días aviso</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documentos as $doc)
                                <tr>
                                    <td>{{ $doc->nombre_doc }}</td>
                                    <td>{{ $doc->es_obligatorio ? 'Sí' : 'No' }}</td>
                                    <td>{{ $doc->requiere_vencimiento ? 'Sí' : 'No' }}</td>
                                    <td>{{ $doc->dias_aviso }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4">Sin datos</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $documentos->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
    @include('shared.sidebars.user')
@endsection
