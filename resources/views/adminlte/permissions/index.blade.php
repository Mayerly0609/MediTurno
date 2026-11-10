@extends('adminlte::page')

@section('title', 'Permisos')


@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-1">
        <div class="d-flex align-items-center justify-content-center">
            <i class="fas fa-shield-alt text-primary mr-2 fa-2x"></i>
            <span>
                <span class="font-weight-bold h4"> Permisos </span> <br>
                <small class="form-text text-muted text-sm">  Administra los permisos y las acciones disponibles en el sistema.</small>
            </span>
        </div>
        <button type="button"
                class="btn btn-primary"
                id="nuevoRegistro"
                data-modal="modalNuevoPermiso">
            <i class="fas fa-plus mr-1"></i>
            Nuevo permiso
        </button>
    </div>
@stop


@section('content')

<div class="row">

    {{-- ========================================================= --}}
    {{-- PERMISOS                                                   --}}
    {{-- ========================================================= --}}

    <div class="col-lg-4">

        <x-adminlte-card title="Permisos" theme="light" class="shadow-sm">

            {{-- Buscador --}}
            
            <div class="container-input-search mb-2">
                <input type="text" placeholder="Search"  id="searchRegister" name="text" class="inputSearch">
                <i class="fas fa-search"></i>
            </div>

            {{-- Lista de permisos --}}
            <div id="listaRegistros" data-url = "/admin/permissions/getActions/">

                @foreach($permissions_created as $permission)

                    <div class="registro-item" data-id="{{ $permission->id }}"  data-search="{{ strtolower($permission->name ) }}">

                        <div class="registro-info d-flex">
                            <button class="btn btn-link text-primary py-0 px-2 border-0 btn-edit shadow-none text-decoration-none" 
                                type="button"
                                data-id="{{ $permission->id }}"
                                data-name="{{ $permission->name }}"
                                data-description="{{ $permission->label }}"
                                data-origen = "permisos">
                                <i class="fas fa-edit"></i>
                            </button>

                            <div class="registro-name">
                                {{ $permission->name }}
                            </div>

                            <div hidden class="registro-description">
                                    {{ $permission->label }}
                            </div>

                        </div>

                        <div class="d-flex align-items-center">
                            <div class="badges">
                                <button class="{{ $permission->actions_count > 0 ? 'blue' : 'badge-light text-muted border' }}"  data-id="{{ $permission->id }}" >{{ $permission->actions_count }}</button>
                            </div>

                            <i class="fas fa-chevron-right ml-2 text-muted"></i>

                        </div>

                    </div>

                @endforeach

            </div>

        </x-adminlte-card>

    </div>


    {{-- ========================================================= --}}
    {{-- PERMISO / ACCIONES                                        --}}
    {{-- ========================================================= --}}

    <div class="col-lg-8">

        <x-adminlte-card
            theme="light"
            class="shadow-sm"
            id="permissionDetails">

            {{-- Estado inicial --}}
            <div id="emptyRegister"
                 class="text-center py-5">

                <i class="fas fa-hand-pointer fa-3x text-muted mb-3"></i>

                <h5 class="text-muted">
                    Selecciona un permiso
                </h5>

                <p class="text-muted mb-0">
                    Selecciona un permiso de la lista para administrar
                    sus acciones.
                </p>

            </div>


            {{-- Contenido del permiso --}}
            <div id="registerContent" style="display:none;">

                {{-- Cabecera del permiso --}}
                <div class="d-flex py-2 px-2 mb-1 justify-content-between">

                    <div class="d-flex align-items-center">

                        <div class="permission-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>

                        <div class="ml-3">

                            <h4 id="selectedRegisterName"
                                class="mb-1">

                            </h4>

                            <span id="selectedRegisterDescription"
                                  class="text-muted">

                            </span> 
                            
                        </div>
                        
                        
                        
                    </div>
                    
                </div>


                {{-- Acciones --}}
                <div class="card card-outline card-primary mb-0">

                    <div class="card-header">

                        <div class="d-flex align-items-center justify-content-between">

                            <h5 class="card-title mb-0">

                                <i class="fas fa-list-check mr-2"></i>
                                Acciones disponibles

                            </h5>

                            <div class="col-md-8">

                                <div class="container-input-search mb-2">
                                    <input type="text" placeholder="Buscar acción..."  id="searchRegisterDisp" name="text" class="inputSearch">
                                    <i class="fas fa-search"></i>
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        {{-- Buscador de acciones --}}
                        <div class="row mb-3">


                        </div>


                        {{-- FORMULARIO --}}
                        <form method="POST" id="formAsignacion" data-form="permisos">

                            @csrf

                            <div id="actionsContainer">
                                @foreach($actions as $action)

                                    <label class="registro-disponible"  data-search="{{ strtolower($action->name .' ' .$action->route ) }}">
                                            
                                            <label class="ios-checkbox blue px-3">
                                                <input type="checkbox" 
                                                class="row-checkbox registro-checkbox" 
                                                name="actions[]" 
                                                id="registro_{{ $action->id }}"
                                                value="{{ $action->id }}"/>
                                                <div class="checkbox-wrapper">
                                                    <div class="checkbox-bg"></div>
                                                    <svg fill="none" viewBox="0 0 24 24" class="checkbox-icon">
                                                        <path
                                                            stroke-linejoin="round"
                                                            stroke-linecap="round"
                                                            stroke-width="3"
                                                            stroke="currentColor"
                                                            d="M4 12L10 18L20 6"
                                                            class="check-path"
                                                        ></path>
                                                    </svg>
                                                </div>
                                            </label>
                                            <small class="action-route d-block">
                                                {{ $action->route }}
                                            </small>
                                    </label>

                                @endforeach
                            </div>

                        </form>

                    </div>


                    {{-- Footer --}}
                    <div class="card-footer">

                        <div class="d-flex align-items-center">

                            <div class="text-muted">

                                <i class="fas fa-check-circle text-primary mr-1"></i>

                                <span id="footerSelectedCount">
                                    0 acciones seleccionadas
                                </span>

                            </div>

                            <div class="ml-auto">

                                <button type="submit"
                                        form="formAsignacion"
                                        class="btn btn-primary"
                                        id="btnSaveActions"
                                        disabled>

                                    <i class="fas fa-save mr-1"></i>

                                    Guardar cambios

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </x-adminlte-card>

    </div>

</div>


{{-- ============================================================= --}}
{{-- MODAL NUEVO PERMISO                                           --}}
{{-- ============================================================= --}}

<x-adminlte-modal
    id="modalNuevoPermiso"
    title="Nuevo permiso"
    theme="primary"
    icon="fas fa-plus"
    size="md">

    <form method="POST" id="formPermission" action="{{ route('adminlte.permissions.store') }}">
        @csrf
            <input type="hidden" name="_method" id="methodPermission" value="POST">

            <x-adminlte-input name="name" label="{{ __('adminlte.name') }}" id="name" />
            <x-adminlte-textarea rows="3" id="label"  label="Descripción" name="label"></x-adminlte-textarea>
                
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button class="btn btn-outline-info" type="submit" id="btnGuardarPermiso"> <i class="fas fa-save"></i> Crear Permiso</button>
                
    </form>

  

</x-adminlte-modal>

@stop


