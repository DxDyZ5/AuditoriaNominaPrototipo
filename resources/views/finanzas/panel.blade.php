@extends('layouts.app')

@section('site_title', formatTitle(['Hallazgos financieros', config('settings.title')]))

@section('content')
<div class="bg-base-1 flex-fill">
    <div class="container py-3 my-3">
        @include('shared.breadcrumbs', ['breadcrumbs' => [
            ['url' => route('dashboard'), 'title' => 'Inicio'],
            ['title' => 'Hallazgos financieros'],
        ]])

        <div class="d-flex align-items-end">
            <h1 class="h2 mb-3 flex-grow-1 text-truncate">Hallazgos financieros</h1>
            <div class="d-flex">
                <form method="GET" action="{{ route('finanzas.panel') }}" class="mr-2">
                    <select name="tipo" class="form-control">
                        <option value="">Todos</option>
                        <option value="documentos" @if(request()->input('tipo')=='documentos') selected @endif>Documentos faltantes</option>
                        <option value="dinero" @if(request()->input('tipo')=='dinero') selected @endif>Errores de dinero</option>
                    </select>
                </form>
                <form method="POST" action="{{ route('finanzas.ejecutar') }}">
                    @csrf
                    <button class="btn btn-primary">Ejecutar Auditoría</button>
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
                                <th>Empleado</th>
                                <th>Tipo</th>
                                <th>Monto diferencia</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($auditorias as $a)
                                <tr>
                                    <td>{{ optional($a->empleado)->nombre }}</td>
                                    <td>{{ $a->tipo_error }}</td>
                                    <td>{{ $a->monto_diferencia }}</td>
                                    <td>{{ $a->created_at }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4">Sin datos</td></tr>
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
