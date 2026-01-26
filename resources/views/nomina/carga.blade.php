@extends('layouts.app')

@section('site_title', formatTitle(['Carga de nómina', config('settings.title')]))

@section('content')
<div class="bg-base-1 flex-fill">
    <div class="container py-3 my-3">
        @include('shared.breadcrumbs', ['breadcrumbs' => [
            ['url' => route('dashboard'), 'title' => 'Inicio'],
            ['title' => 'Carga de nómina'],
        ]])

        <h1 class="h2 mb-3">Carga de nómina</h1>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('nomina.importar') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="col-12 col-md-3 mb-3">
                            <label>Mes</label>
                            <select name="mes" class="form-control" required>
                                @for($m=1;$m<=12;$m++)
                                    <option value="{{ $m }}">{{ $m }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-12 col-md-3 mb-3">
                            <label>Año</label>
                            <input type="number" name="año" class="form-control" value="{{ now()->year }}" required>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label>Archivo CSV</label>
                            <input type="file" name="archivo" class="form-control" required>
                            <div class="small text-muted mt-1">Formato: empleado_id, monto_pagado, metodo_pago</div>
                        </div>
                        <div class="col-12 col-md-2 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="bloquear_pago" id="bloquear_pago" checked>
                                <label class="form-check-label" for="bloquear_pago">Bloquear pago sin contrato</label>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary">Importar</button>
                </form>
            </div>
        </div>
    </div>
    @include('shared.sidebars.user')
@endsection
