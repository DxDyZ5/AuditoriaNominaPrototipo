@extends('layouts.app')

@section('site_title', formatTitle(['Hallazgos', config('settings.title')]))

@section('content')
<div class="bg-base-1 flex-fill">
    <div class="container py-3 my-3">
        @include('shared.breadcrumbs', ['breadcrumbs' => [
            ['url' => route('dashboard'), 'title' => 'Inicio'],
            ['title' => 'Hallazgos'],
        ]])

        <div class="d-flex align-items-end">
            <h1 class="h2 mb-3 flex-grow-1 text-truncate">Hallazgos</h1>
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
                                <th>Auditoría</th>
                                <th>Documento</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hallazgos as $h)
                                <tr>
                                    <td>{{ $h->auditoria_id }}</td>
                                    <td>{{ optional($h->documento)->nombre_doc }}</td>
                                    <td>{{ $h->tipo }}</td>
                                    <td>{{ $h->estado }}</td>
                                    <td>
                                        @if($h->estado !== 'cerrado')
                                            <form method="POST" action="{{ route('hallazgos.cerrar', $h->id) }}">
                                                @csrf
                                                <button class="btn btn-sm btn-success">Cerrar</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5">Sin datos</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $hallazgos->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
    @include('shared.sidebars.user')
@endsection
