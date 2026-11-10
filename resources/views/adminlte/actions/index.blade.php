@extends('adminlte::page')

@section('title', 'Acciones')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="m-0">{{ __('adminlte.actions') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('adminlte.home') }}</a></li>
                <li class="breadcrumb-item">{{ __('adminlte.administration') }}</li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('adminlte.actions') }}</li>
            </ol>
        </div>
    </div>
@stop

@section('content')

    <x-adminlte-card icon="fas fa-cubes">
        <button
            type="button"
            class="btn btn-primary float-end"
            id="btnNuevaAccion" >
            <i class="fas fa-plus"></i>
            Nueva acción
        </button>

        {{ $dataTable->table([
            'class' => 'table table-bordered table-hover table-sm',
        ], true) }}

        

    </x-adminlte-card>

@stop

@push('js')

    {!! $dataTable->scripts(attributes: ['type' => 'module']) !!}

    <script>
        window.routes = {
            actionsStore: @json(route('adminlte.actions.store')),
            actionsUpdate: @json(route('adminlte.actions.update', ['action' => '__ID__'])),
            actionsDelete: @json(route('adminlte.actions.destroy', ['action' => '__ID__']))
        };
    </script>

@endpush