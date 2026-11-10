@extends('adminlte::page')

@section('title', 'Proyecto #' . $project->id)
@vite(['resources/js/projects.js'])



@section('content_header')

    <div class="align-items-start d-flex gap-12 justify-content-between mb-2 px-3 project-header">

        <div>

            <div class="d-flex align-items-center gap-2">

                <h1 class="mb-0 fs-5 fw-bold mb-0 mb-0 text-dark mr-6">
                    Proyecto #{{ $project->id }}
                </h1>
                <h4 class="fs-4 fw-bold text-teal  text-capitalize">
                    {{ $project->name }}
                </h4>

                <span class="fs-8 fw-semibold px-3 py-2 rounded-3 status-active">
                    @switch($project->status)
                        @case('active')
                                Activo
                            @break
                        @case('planning')
                                Planificación
                            @break
                        @case('paused')
                                Pausado
                            @break
                        @case('completed')
                                Completado
                            @break
                        @case('cancelled')
                                Cancelado
                                @break
                                @endswitch
                            </span>
           

            </div>




            @if($project->description)
                <p class="page-subtitle mb-0">
                    {{ $project->description }}
                </p>
            @endif
        </div>


        <div class="align-items-center d-flex gap-2 project-actions">

            <a href="{{ route('projects.edit', $project) }}"
               class="btn btn-light project-action">

                <i class="fas fa-pen"></i>
                Editar Proyecto

            </a>


            <button
                type="button"
                class="btn btn-t1-primary "
                data-bs-toggle="modal"
                data-bs-target="#modalNewActivity"
            >

                <i class="fas fa-plus me-1"></i>

                Agregar actividad

            </button>


            <button
                type="button"
                class="btn btn-outline-secondary"
                title="Más opciones"
            >

                <i class="fas fa-ellipsis-v"></i>

            </button>

        </div>

    </div>
@stop
 @section('content')

<div class="container-fluid project-detail-page">

    {{-- ========================================================= --}}
    {{-- INFORMACIÓN GENERAL --}}
    {{-- ========================================================= --}}

    <div class="project-info-card">
        <div class="project-info-item project-leader">
            <div class="project-avatar">
                <i class="fas fa-user"></i>
            </div>

            <div>
                <span>Líder del Proyecto</span>
                <strong>{{ $project->leader->name }}</strong>
            </div>

        </div>


        <div class="project-info-item">
            <i class="far fa-user-circle project-info-icon"></i>

            <div>
                <span>Creado por</span>
                <strong>{{ $project->creator->name }}</strong>
            </div>

        </div>


        <div class="project-info-item">
            <i class="far fa-calendar-alt project-info-icon"></i>
            <div>
                <span>Fecha de Creación</span>
                <strong>{{ $project->created_at
                                ? $project->created_at->format('d M Y H:i')
                                : '-'
                            }}
                </strong>
            </div>
        </div>


        <div class="project-info-item">
            <i class="far fa-calendar-check project-info-icon"></i>
            <div>
                <span>Fecha de Finalización</span>
                <strong>{{ $project->end_date
                                ? $project->end_date->format('d M Y H:i')
                                : '-' }}</strong>
            </div>

        </div>


        <div class="project-info-item">
            <div>
            </div>
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- KPIs --}}
    {{-- ========================================================= --}}
    
                    
    </div>
    <div class="row g-3 mb-3">


        {{-- Progreso
        <div class="col-xl-3 col-md-6">
            <div class="project-kpi-card">
                
                <div class="progress-circle">
                    <div id="projectProgressChart" data-progress="{{ $progress }}"></div>

                    <strong>{{ $progress }}%</strong>

                </div>

                <div class="kpi-content">

                    <span class="kpi-title">
                        Progreso General
                    </span>

                    <strong class="kpi-main">
                        Completado
                    </strong>

                    <small class="kpi-positive">
                        <i class="fas fa-arrow-up"></i>
                        {{ $progress }}%
                    </small>

                </div>

            </div>

        </div> --}}

        <div class="col-xl-3 col-md-6">
            <div class="project-kpi-card">

                <div class="progress-circle">
                    <div
                        id="projectProgressChart"
                        data-progress="{{ $progress }}">
                    </div>
                </div>

                <div class="kpi-content">
                    <span class="kpi-title">
                        Progreso General
                    </span>

                    <strong class="kpi-main">
                        Completado
                    </strong>

                    <small class="kpi-positive">
                        <i class="fas fa-arrow-up"></i>
                        {{ $progress }}%
                    </small>
                </div>

            </div>
        </div>

        {{-- Actividades --}}
        <div class="col-xl-3 col-md-6">

            <div class="project-kpi-card">

                <div class="kpi-icon kpi-icon-blue">
                    <i class="fas fa-list-ul"></i>
                </div>

                <div class="kpi-content">

                    <strong class="kpi-number">
                        {{ $totalActivities }}
                    </strong>

                    <span class="kpi-label">
                        Actividades
                    </span>

                    <a href="#">
                         {{ $inProgressActivities }} en progreso
                    </a>

                </div>

            </div>

        </div>

       

       

        {{-- Tiempo --}}
        <div class="col-xl-3 col-md-6">

            <div class="project-kpi-card">

                <div class="kpi-icon kpi-icon-orange">
                    <i class="far fa-clock"></i>
                </div>

                <div class="kpi-content">

                    @php

                        $daysRemaining = null;
                        if ($project->end_date) {
                            $daysRemaining = max( 0, now()->diffInDays( $project->end_date,false));
                        }
                    @endphp

                    <div>
                        <strong class="kpi-number">
                            {{ number_format($daysRemaining,0,',','.') ?? '-' }}
                        </strong>

                        <span class="kpi-days">
                            días
                        </span>
                    </div>

                    <span class="kpi-label">
                        Tiempo restante
                    </span>

                    <small>
                        para la finalización
                    </small>

                </div>

            </div>

        </div>


        {{-- Riesgos --}}
        <div class="col-xl-3 col-md-6">

            <div class="project-kpi-card">

                <div class="kpi-icon kpi-icon-red">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>

                <div class="kpi-content">

                    <strong class="kpi-number">
                        2
                    </strong>

                    <span class="kpi-label">
                        Actividades Vencidas
                    </span>

                    <a href="#">
                        Ver detalle
                    </a>

                </div>

            </div>

        </div>

    </div>

     {{-- ===================================================== --}}
        {{-- CONTENIDO PRINCIPAL --}}
        {{-- ===================================================== --}}

        <div class="col-xl-9">

            {{-- PROCESO --}}
            <div class="card  ">

                <div >
                    <h3 class="fs-7 mt-2">Fases del Proyecto</h3>
                </div>

                <div class="project-stepper">

                    @foreach($phases as $index => $phase)

                        @php
                            $totalActivities = $phase->activities_count;
                            $completedActivities = $phase->completed_activities_count;

                            $phaseProgress = $totalActivities > 0 ? round($phase->activities_avg_progress ?? 0) : 0;

                            if ($totalActivities === 0) {
                                $phaseStatus = 'Sin actividades';
                                $phaseColor = '';
                            } elseif ($completedActivities === $totalActivities) {
                                $phaseStatus = 'Completada';
                                $phaseColor = 'completed';
                                $phaseProgress = 100;
                            } elseif ($phaseProgress > 0) {
                                $phaseStatus = 'En curso';
                                $phaseColor = 'current';
                            } else {
                                $phaseStatus = 'Pendiente';
                                $phaseColor = '';
                            }
                        @endphp

                        @switch($phase->order)
                            @case('1')
                                @php
                                    $iconPhase = 'fas fa-flag';
                                @endphp
                            @break
                            @case('2')
                                @php
                                    $iconPhase = 'far fa-calendar-alt';
                                @endphp
                            @break
                            @case('3')
                                @php
                                    $iconPhase = 'fas fa-cog';
                                @endphp
                            @break
                            @case('4')
                                @php
                                    $iconPhase = 'fas fa-chart-line';
                                @endphp
                            @break
                            @case('5')
                                @php
                                    $iconPhase = 'fas fa-box-open';
                                @endphp
                            @break
                            @default
                                @php
                                    $iconPhase = 'fas fa-flag-checkered';
                                @endphp
                                @break
                        
                        @endswitch

                       
                        <div class="project-step {{ $phaseColor }}">

                            <div class="step-circle">
                                <i class="{{ $iconPhase }}"></i>
                                
                                @if ( $phaseColor == 'completed' )
                                    <span class="step-check">
                                        <i class="fas fa-check"></i>
                                    </span>
                                @endif
                            </div>

                            
                            <b>{{ $phase->name }}</b>

                            <span class="fs-8">{{ $phaseStatus }}</span>

                        </div>

                            

                    @endforeach
                </div>

            </div>


            {{-- ACTIVIDADES --}}
            <div class="card">
               
                {{ $dataTable->table([
                    'class' => 'table table-hover align-middle datatable',
                ]) }}

            </div>

        </div>

<x-adminlte-modal
    id="modalNewActivity"
    title="Nueva Actividad"
    theme="primary"
    icon="fas fa-plus"
    size="lg"
    >

    <x-slot name="icon">fa-solid fa-folder-plus</x-slot>
    <x-slot name="subtitle">Registrar nueva actividad al proyecto</x-slot>

    @include('activitys.create',[ 'users'=>$projectMembers, 'project' => $project
    ])
  

</x-adminlte-modal>

@stop
    

@section('js')
    @yield('projects.js')
    {{ $dataTable->scripts() }}
@stop
    


