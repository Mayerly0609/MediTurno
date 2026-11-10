@extends('adminlte::page')

@vite(['resources/js/app.js', 'resources/css/app.css'])

@push('css')
    <!-- Estilos de jsTree -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css" />
@endpush

@section('content')

    <div class="row">
        <div class="col-md-6">
            <h4>Prueba Select2 - AdminLTE 4 <span class="text-danger">pte Probar ajax</span> </h4>
    
            <label for="cliente" class="form-label">Selecciona un Rol</label>
            <select id="cliente" class="form-select select2"></select>
        </div>
    
        <div class="col-md-6">
    
            <h4>Prueba DatePicker - AdminLTE 4</h4>
        
            <x-adminlte-input-flatpickr
                name="fecha"
                label="Seleccione una fecha"
                placeholder="Seleccione una fecha"
            />
        </div>

         <div class="col-md-6">
            <h4>Jstree Arbol</h4>

            <div id="jstree-container"></div>

                <!-- jQuery y jsTree JS -->
                
        </div>

        <div class="col-md-6">
            <h4>Yajra DataTables</h4>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Usuarios</h3>
                </div>

                <div class="card-body">

                    {!! $dataTable->table(['class' => ''], true) !!}

                </div>
            </div>
        </div>

        <div class="col-md-6">
            
        </div>

    </div>


    <hr>




@stop


@push('js')
    {!! $dataTable->scripts(attributes: ['type' => 'module']) !!}

   
@endpush

