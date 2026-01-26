@extends('layouts.app')

@section('site_title', formatTitle([__('Recursos Humanos'), config('settings.title')]))

@section('content')
<div class="bg-base-1 flex-fill">
    <div class="bg-base-0">
        <div class="container py-5">
            <div class="row m-n2">
                <div class="d-flex col-12 p-2">
                    <div class="flex-shrink-1">
                        <a href="{{ route('account') }}" class="d-block"><img src="{{ gravatar(Auth::user()->email, 128) }}" class="rounded-circle width-16 height-16"></a>
                    </div>
                    <div class="flex-grow-1 d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-3' : 'ml-3') }}">
                        <div>
                            <h4 class="font-weight-medium mb-1">Bienvenido, {{ Auth::user()->name }}</h4>
                            <div class="text-muted">Sistema de Recursos Humanos</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-base-1">
        <div class="container py-3 my-3">
            <div class="row">
                <div class="col-12 col-lg">
                    <h4 class="mb-0">Cumplimiento</h4>
                </div>
            </div>

            <div class="card border-0 rounded-top shadow-sm my-3 overflow-hidden mb-5">
                <div class="px-3">
                    <div class="row">
                        <div class="col-12 col-lg-auto d-none d-lg-flex align-items-center border-bottom border-bottom-lg-0 {{ (__('lang_dir') == 'rtl' ? 'border-left-lg' : 'border-right-lg') }}">
                            <div class="px-2 py-4 d-flex">
                                <div class="d-flex position-relative text-primary width-10 height-10 align-items-center justify-content-center flex-shrink-0">
                                    <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-xl"></div>
                                    @include('icons.space-dashboard', ['class' => 'fill-current width-5 height-5'])
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg">
                            <div class="px-1 py-4">
                                <div class="d-flex align-items-center pb-1">
                                    <div class="flex-grow-1 mx-2 d-flex text-truncate">
                                        <span class="text-secondary font-weight-medium d-flex align-items-center text-truncate">
                                            <span class="d-inline-block text-truncate">Semáforo de cumplimiento</span>
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center text-muted mb-0 text-truncate flex-shrink-0">
                                        {{ number_format($cumplimientoPromedio, 0, __('.'), __(',')) }}%
                                    </div>
                                </div>
                                <div class="progress height-1.25 w-100 mt-2">
                                    @php
                                        $color = $cumplimientoPromedio >= 90 ? 'bg-success' : ($cumplimientoPromedio >= 60 ? 'bg-warning' : 'bg-danger');
                                    @endphp
                                    <div class="progress-bar {{ $color }} rounded" role="progressbar" style="width: {{ $cumplimientoPromedio }}%" aria-valuenow="{{ $cumplimientoPromedio }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h4 class="mb-3">Estado del sistema</h4>
            <div class="row m-n2">
                <div class="col-12 col-lg-4 p-2">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header align-items-center">
                            <div class="row"><div class="col"><div class="font-weight-medium py-1">Empleados activos</div></div></div>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <div class="display-4">{{ number_format($totalEmpleados, 0, __('.'), __(',')) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 p-2">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header align-items-center">
                            <div class="row"><div class="col"><div class="font-weight-medium py-1">Hallazgos abiertos</div></div></div>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <div class="display-4">{{ number_format($hallazgosAbiertos, 0, __('.'), __(',')) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 p-2">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header align-items-center">
                            <div class="row"><div class="col"><div class="font-weight-medium py-1">Documentos por vencer (30 días)</div></div></div>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <div class="display-4">{{ number_format($vencimientos, 0, __('.'), __(',')) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-n2 mt-1">
                <div class="col-12 col-lg-6 p-2">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header align-items-center">
                            <div class="row"><div class="col"><div class="font-weight-medium py-1">KPI de desvío (Pagado - Proyectado)</div></div></div>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <div class="display-4">{{ number_format($kpiDesvio ?? 0, 2, __('.'), __(',')) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 p-2">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header align-items-center">
                            <div class="row"><div class="col"><div class="font-weight-medium py-1">Alertas de fantasmas (pagos sin estado Activo)</div></div></div>
                        </div>
                        <div class="card-body">
                            @if(isset($fantasmas) && count($fantasmas) > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead><tr><th>Empleado</th><th>Monto</th></tr></thead>
                                        <tbody>
                                            @foreach($fantasmas as $f)
                                                <tr>
                                                    <td>{{ optional($f->empleado)->nombre }} ({{ optional($f->empleado)->estado }})</td>
                                                    <td>{{ number_format($f->monto_pagado, 2, __('.'), __(',')) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-muted">Sin alertas en la última nómina.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@include('shared.sidebars.user')
