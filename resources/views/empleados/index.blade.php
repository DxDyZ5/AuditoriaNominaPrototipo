@extends('layouts.app')

@section('site_title', formatTitle(['Empleados', config('settings.title')]))

@section('content')
<div class="bg-base-1 flex-fill">
    <div class="container py-3 my-3">
        @include('shared.breadcrumbs', ['breadcrumbs' => [
            ['url' => route('dashboard'), 'title' => 'Inicio'],
            ['title' => 'Empleados'],
        ]])

        <div class="d-flex align-items-end">
            <h1 class="h2 mb-3 flex-grow-1 text-truncate">Empleados</h1>
            <a href="{{ route('empleados.crear') }}" class="btn btn-primary">Nuevo empleado</a>
        </div>

        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header">
                <div class="row">
                    <div class="col"><div class="font-weight-medium py-1">Importación CSV</div></div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('importacion.empleados') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <input type="file" name="archivo" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-outline-primary">Importar</button>
                        </div>
                    </div>
                    <div class="small text-muted">Columnas: nombre, puesto, departamento, fecha_ingreso, estado</div>
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
                <form method="GET" action="{{ route('empleados') }}" class="mb-3">
                    <div class="form-row">
                        <div class="col">
                            <input type="text" name="q" class="form-control" placeholder="Buscar por nombre" value="{{ app('request')->input('q') }}">
                        </div>
                        <div class="col">
                            <select name="estado" class="form-control">
                                <option value="">Estado</option>
                                <option value="activo" @if(app('request')->input('estado')=='activo') selected @endif>Activo</option>
                                <option value="inactivo" @if(app('request')->input('estado')=='inactivo') selected @endif>Inactivo</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-outline-primary">Filtrar</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Puesto</th>
                                <th>Departamento</th>
                                <th>Fecha ingreso</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($empleados as $empleado)
                                <tr>
                                    <td>{{ $empleado->nombre }}</td>
                                    <td>{{ optional($empleado->puesto)->nombre_puesto }}</td>
                                    <td>{{ optional($empleado->puesto)->departamento }}</td>
                                    <td>{{ $empleado->fecha_ingreso }}</td>
                                    <td>{{ $empleado->estado }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('auditorias.ejecutar', $empleado) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-primary">Auditar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6">Sin datos</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $empleados->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
    @include('shared.sidebars.user')
@endsection
