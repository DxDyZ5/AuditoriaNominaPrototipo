@extends('layouts.app')

@section('site_title', formatTitle(['Configuración de reglas', config('settings.title')]))

@section('content')
<div class="bg-base-1 flex-fill">
    <div class="container py-3 my-3">
        @include('shared.breadcrumbs', ['breadcrumbs' => [
            ['url' => route('dashboard'), 'title' => 'Inicio'],
            ['title' => 'Configuración de reglas'],
        ]])

        <h1 class="h2 mb-3">Configuración de reglas</h1>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="mb-2">País: {{ config('compliance.pais') }}</div>
                        <div class="mb-2">SFS (empleado): {{ number_format(config('compliance.sfs') * 100, 2) }}%</div>
                        <div class="mb-2">AFP (empleado): {{ number_format(config('compliance.afp') * 100, 2) }}%</div>
                        <div class="small text-muted"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('shared.sidebars.user')
@endsection
