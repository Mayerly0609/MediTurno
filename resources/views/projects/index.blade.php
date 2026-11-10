@extends('adminlte::page')

@section('title', 'Mis Proyectos')

@section('content_header')

    <div class="d-flex justify-content-between align-items-center">

        <div>
            <h1 class="page-title mb-1">
                Proyectos
            </h1>

            <p class="page-subtitle mb-0">
                Gestiona y da seguimiento a todos los proyectos asignados.
                Aquí encontrarás el estado, avance y detalles clave de cada uno.
            </p>
        </div>

        <div>
            <button type="button" class="btn btn-t1-primary" data-bs-toggle="modal" data-bs-target="#modalNewProject">
            <i class="fas fa-plus me-1"></i> Nuevo Proyecto
            </button>
                
        </div>

    </div>

@stop


@section('content')

<div class="container-fluid px-0">

    {{-- ==========================================================
         KPIs
    =========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm kpi-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="kpi-label">
                                Total de proyectos
                            </span>
                            <h3 class="kpi-value">
                                {{ $totalProjects }}
                            </h3>
                            <span class="kpi-description">
                                Proyectos registrados
                            </span>
                        </div>

                        <div class="kpi-icon bg-primary-subtle text-primary">
                            <i class="bi bi-folder2-open"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- En curso --}}
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm kpi-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="kpi-label">
                                En curso
                            </span>
                            <h3 class="kpi-value">
                                {{ $activeProjects }}
                            </h3>
                            <span class="kpi-description">
                                Proyectos activos
                            </span>
                        </div>
                        <div class="kpi-icon bg-success-subtle text-success">
                            <i class="bi bi-play-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Planificación --}}
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm kpi-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="kpi-label">
                                En planificación
                            </span>

                            <h3 class="kpi-value">
                                {{ $planningProjects }}
                            </h3>

                            <span class="kpi-description">
                                Próximos proyectos
                            </span>
                        </div>
                        <div class="kpi-icon bg-warning-subtle text-warning">
                            <i class="bi bi-calendar3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Finalizados --}}
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm kpi-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="kpi-label">
                                Finalizados
                            </span>
                            <h3 class="kpi-value">
                                {{ $completedProjects }}
                            </h3>
                            <span class="kpi-description">
                                Proyectos completados
                            </span>
                        </div>
                        <div class="kpi-icon bg-info-subtle text-info">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ==========================================================
         TABLA
    =========================================================== --}}

    <div class="card border-0 shadow-sm projects-card">

       
        <div class="card-body px-4">

            {{-- Filtros --}}
            <div class=" mb-4">

                <!-- <div class="d-flex gap-2 justify-content-end">

                    <button 
                        type="button"
                        class="btn btn-outline-secondary btn-sm"
                        id="btn-refresh-projects">

                        <i class="bi bi-arrow-clockwise me-1"></i>

                        Actualizar
                    </button>

                    <button
                        type="button"
                        class="btn btn-outline-secondary btn-sm"
                        id="btn-clear-filters">

                        <i class="bi bi-funnel me-1"></i>

                        Limpiar filtros
                    </button>

                </div>

                <div class="row g-3">

                    <div class="col-12 col-lg-4">

                        <label class="form-label small fw-semibold">
                            Buscar proyecto
                        </label>

                        <div class="input-group ">

                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>

                            <input
                                type="text"
                                id="project-search"
                                class="form-control"
                                placeholder="Nombre o descripción...">

                        </div>

                    </div>


                    <div class="col-12 col-md-4 col-lg-2">

                        <label class="form-label small fw-semibold">
                            Estado
                        </label>

                        <select
                            id="filter-status"
                            class="form-select">

                            <option value="">
                                Todos
                            </option>

                            <option value="active">
                                Activo
                            </option>

                            <option value="planning">
                                Planificación
                            </option>

                            <option value="completed">
                                Finalizado
                            </option>

                            <option value="cancelled">
                                Cancelado
                            </option>

                        </select>

                    </div>


                    <div class="col-12 col-md-4 col-lg-3">

                        <label class="form-label small fw-semibold">
                            Tipo de proyecto
                        </label>

                        <select
                            id="filter-type"
                            class="form-select">

                            <option value="">
                                Todos
                            </option>

                            <option value="Desarrollo">
                                Desarrollo
                            </option>

                            <option value="Mejora">
                                Mejora
                            </option>

                            <option value="Automatización">
                                Automatización
                            </option>

                            <option value="Integración">
                                Integración
                            </option>

                            <option value="Migración">
                                Migración
                            </option>

                        </select>

                    </div>


                    <div class="col-12 col-md-4 col-lg-3">

                        <label class="form-label small fw-semibold">
                            Fecha de inicio
                        </label>

                        <input
                            type="date"
                            id="filter-start-date"
                            class="form-control">

                    </div>

                </div> -->

                {{-- DataTable --}}
                <div class="">
    
                    {{ $dataTable->table([
                        'class' => 'table align-middle datatable',
                        'width' => '100%',
                    ], true) }}
    
                </div>
            </div>



        </div>

    </div>

</div>

@stop



@section('js')
    {{ $dataTable->scripts() }}
@stop


<x-adminlte-modal
    id="modalNewProject"
    title="Nuevo Proyecto"
    theme="primary"
    icon="fas fa-plus"
    {{-- subtitle = "Descripcion" --}}
    size="lg">
    <x-slot name="icon">fa-solid fa-folder-plus</x-slot>
    <x-slot name="subtitle">Registrar nuevo proyecto</x-slot>

    @include('projects.create',['users' => $users])
  

</x-adminlte-modal>