@extends('layouts.app')

@section('site_title', formatTitle(['Nuevo empleado', config('settings.title')]))

@section('content')
<div class="bg-base-1 flex-fill">
    <div class="container py-3 my-3">
        @include('shared.breadcrumbs', ['breadcrumbs' => [
            ['url' => route('dashboard'), 'title' => 'Inicio'],
            ['url' => route('empleados'), 'title' => 'Empleados'],
            ['title' => 'Nuevo empleado'],
        ]])

        <h1 class="h2 mb-3">Nuevo empleado</h1>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('empleados.guardar') }}">
                    @csrf
                    <div class="form-row">
                        <div class="col-12 col-md-6 mb-3">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label>Puesto</label>
                            <select name="puesto_id" class="form-control">
                                <option value="">Sin puesto</option>
                                @foreach($puestos as $puesto)
                                    <option value="{{ $puesto->id }}">{{ $puesto->nombre_puesto }} @if($puesto->departamento) ({{ $puesto->departamento }}) @endif</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label>Fecha de ingreso</label>
                            <input type="date" name="fecha_ingreso" class="form-control">
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label>Estado</label>
                            <select name="estado" class="form-control">
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label>Salario diario</label>
                            <input type="number" step="0.01" name="salario_diario" class="form-control">
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label>Tipo de pago</label>
                            <select name="tipo_pago" class="form-control">
                                <option value="">Sin definir</option>
                                <option value="quincenal">Quincenal</option>
                                <option value="mensual">Mensual</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label>Cuenta bancaria (hash)</label>
                            <input type="text" name="cuenta_bancaria_hash" class="form-control" maxlength="64">
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label>Fecha último incremento</label>
                            <input type="date" name="fecha_ultimo_incremento" class="form-control">
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="bloquear_pago" id="bloquear_pago">
                                <label class="form-check-label" for="bloquear_pago">Bloquear pago si falta contrato</label>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
    @include('shared.sidebars.user')
@endsection
