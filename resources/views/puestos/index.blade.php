@extends('layouts.app')

@section('site_title', formatTitle(['Puestos', config('settings.title')]))

@section('content')
<div class="bg-base-1 flex-fill">
    <div class="container py-3 my-3">
        @include('shared.breadcrumbs', ['breadcrumbs' => [
            ['url' => route('dashboard'), 'title' => 'Inicio'],
            ['title' => 'Puestos'],
        ]])

        <div class="d-flex align-items-end">
            <h1 class="h2 mb-3 flex-grow-1 text-truncate">Puestos</h1>
        </div>

        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header">
                <div class="row">
                    <div class="col"><div class="font-weight-medium py-1">Nuevo puesto</div></div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('puestos.guardar') }}">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <input type="text" name="nombre_puesto" class="form-control" placeholder="Nombre del puesto" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" name="departamento" class="form-control" placeholder="Departamento">
                        </div>
                        <div class="col-md-4 mb-3">
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
                                <th>Departamento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($puestos as $puesto)
                                <tr>
                                    <td>{{ $puesto->nombre_puesto }}</td>
                                    <td>{{ $puesto->departamento }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2">Sin datos</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $puestos->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
    @include('shared.sidebars.user')
@endsection
