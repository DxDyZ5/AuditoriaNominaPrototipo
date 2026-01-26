@extends('layouts.app')

@section('site_title', formatTitle(['Auditorías', config('settings.title')]))

@section('content')
<div class="bg-base-1 flex-fill">
    <div class="container py-3 my-3">
        @include('shared.breadcrumbs', ['breadcrumbs' => [
            ['url' => route('dashboard'), 'title' => 'Inicio'],
            ['title' => 'Auditorías'],
        ]])

        <div class="d-flex align-items-end">
            <h1 class="h2 mb-3 flex-grow-1 text-truncate">Auditorías</h1>
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
                                <th>Empleado</th>
                                <th>Fecha</th>
                                <th>Resultado (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($auditorias as $aud)
                                <tr>
                                    <td>{{ optional($aud->empleado)->nombre }}</td>
                                    <td>{{ $aud->fecha_auditoria }}</td>
                                    <td>{{ $aud->resultado_porcentaje }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3">Sin datos</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $auditorias->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
    @include('shared.sidebars.user')
@endsection
