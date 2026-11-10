<?php

namespace App\DataTables;

use App\Models\Projects\Project;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProjectsDataTable extends DataTable
{
    /**
     * Consulta principal.
     */
    public function query(Project $model): QueryBuilder
    {
        return $model
            ->newQuery()
            ->select([
                'projects.id',
                'projects.name',
                'projects.description',
                'projects.status',
                'projects.leader_id',
                'projects.created_by',
                'projects.start_date',
                'projects.end_date',
            ]);
    }

    /**
     * Procesamiento de DataTables.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))

            ->addColumn('project', function (Project $project) {

                $initials = collect(
                    preg_split('/\s+/', trim($project->name))
                )
                    ->filter()
                    ->take(2)
                    ->map(fn ($word) => mb_substr($word, 0, 1))
                    ->join('');

                return '
                    <div class="project-cell">
                        <div class="project-avatar">
                            ' . e(mb_strtoupper($initials)) . '
                        </div>

                        <div class="project-info">
                            <a href="' . route('projects.show', $project) . '"
                               class="project-name">
                                ' . e($project->name) . '
                            </a>

                            <span class="project-description">
                                ' . e(
                                    \Illuminate\Support\Str::limit(
                                        $project->description,
                                        70
                                    )
                                ) . '
                            </span>
                        </div>
                    </div>
                ';
            })

            // ->addColumn('phases', function (Project $project) {

            //     return '
            //         <div class="phase-list">
            //             <span class="phase-badge phase-completed">
            //                 Inicio
            //             </span>

            //             <span class="phase-badge phase-current">
            //                 Planificación
            //             </span>

            //             <span class="phase-more">
            //                 +4
            //             </span>
            //         </div>
            //     ';
            // })

            ->addColumn('progress_bar', function (Project $project) {

                $progress = (int) $project->progress;

                return '
                    <div class="progress-wrapper">
                        <div class="progress-value">
                            <span>' . $progress . '%</span>
                        </div>

                        <div class="progress project-progress">
                            <div
                                class="progress-bar"
                                role="progressbar"
                                style="width: ' . $progress . '%"
                                aria-valuenow="' . $progress . '"
                                aria-valuemin="0"
                                aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                ';
            })

            ->addColumn('status_badge', function (Project $project) {

                $status = match ($project->status) {
                    'active'      => ['Activo', 'success'],
                    'planning'    => ['Planificación', 'warning'],
                    'completed'   => ['Finalizado', 'primary'],
                    'cancelled'   => ['Cancelado', 'danger'],
                    default       => ['Sin definir', 'secondary'],
                };

                return '
                    <span class="badge rounded-pill text-bg-' .
                    $status[1] . ' project-status">
                        ' . $status[0] . '
                    </span>
                ';
            })

            ->editColumn('start_date', function (Project $project) {

                return $project->start_date ? $project->start_date->format('d/m/Y') : '—';
            })

            ->editColumn('end_date', function (Project $project) {

                return $project->end_date
                    ? $project->end_date->format('d/m/Y')
                    : '—';
            })

            ->addColumn('actions', function (Project $project) {

                return '
                    <div class="dropdown">
                        <button
                            class="btn btn-sm btn-light border"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">

                            <i class="bi bi-three-dots-vertical"></i>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end">

                            <li>
                                <a
                                    class="dropdown-item"
                                    href="' . route('projects.show', $project) . '">
                                    <i class="bi bi-eye me-2"></i>
                                    Ver proyecto
                                </a>
                            </li>

                            <li>
                                <a
                                    class="dropdown-item"
                                    href="' . route('projects.edit', $project) . '">
                                    <i class="bi bi-pencil me-2"></i>
                                    Editar
                                </a>
                            </li>

                            <li>
                                <a
                                    class="dropdown-item"
                                    href="">
                                    <i class="bi bi-list-check me-2"></i>
                                    Actividades
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <button
                                    type="button"
                                    class="dropdown-item text-danger btn-delete-project"
                                    data-id="' . $project->id . '">

                                    <i class="bi bi-trash me-2"></i>
                                    Eliminar
                                </button>
                            </li>

                        </ul>
                    </div>
                ';
            })

            ->filterColumn('project', function ($query, $keyword) {

                $query->where(function ($query) use ($keyword) {

                    $query
                        ->where('projects.name', 'like', "%{$keyword}%")
                        ->orWhere('projects.description', 'like', "%{$keyword}%")
                        ;
                });
            })

            ->rawColumns([
                'project',
                'phases',
                'progress_bar',
                'status_badge',
                'actions',
            ]);
    }

    /**
     * Configuración HTML.
     */
    public function html(): HtmlBuilder
    {
        return $this
            ->builder()
            ->setTableId('projects-table')

            ->columns($this->getColumns())

            ->minifiedAjax(route('projects.data'))

            ->processing(true)
            ->serverSide(true)

            ->responsive(false)
            ->autoWidth(true)

            ->pageLength(10)
            ->searching(true)


            ->orderBy(0, 'asc')
            ->lengthChange(false)

            

            ->parameters([
                'dom' => "<'row '<'col-md-4'><'col-md-4'><'col-md-4'f>>" .
                            "<'row'<'col-12'tr>>" .
                            "<'row mt-1'<'col-md-5 page-subtitle  'i><'col-md-7'p>>",

                'language' => [
                    'processing' => '
                        <div class="dt-processing">
                            <div class="spinner-border spinner-border-sm text-primary"
                                 role="status">
                            </div>
                            <span>Cargando proyectos...</span>
                        </div>
                    ',

                    'search' => '',
                    'searchPlaceholder' => 'Buscar Proyecto ...',
                    // 'lengthMenu' =>
                    //     'Mostrar _MENU_ proyectos',

                    'zeroRecords' =>
                        'No se encontraron proyectos',

                    'emptyTable' =>
                        'No hay proyectos registrados',

                    'info' =>
                        'Mostrando _START_ a _END_ de _TOTAL_ proyectos',

                    'infoEmpty' =>
                        '',

                    'infoFiltered' =>
                        '',

                    'paginate' => [
                        'first' => 'Primero',
                        'last' => 'Último',
                        'next' => 'Siguiente',
                        'previous' => 'Anterior',
                    ],
                ],

                // 'dom' =>
                //     "<'row mb-3'<'col-md-6'l>>rt
                //     <'row align-items-center mt-3'
                //         <'col-md-6'i>
                //         <'col-md-6'p>
                //     >",

                'drawCallback' => 'function() {
                    // Aquí podremos ejecutar comportamientos después
                    // de cada actualización de DataTables.
                }',
            ]);
    }

    /**
     * Columnas.
     */
    protected function getColumns(): array
    {
        return [

            Column::make('project')
                ->title('Proyecto')
                ->name('projects.name')
                ->orderable(true)
                ->searchable(true),

            // Column::make('phases')
            //     ->title('Fases')
            //     ->orderable(false)
            //     ->searchable(false),

            Column::make('progress_bar')
                ->title('Progreso')
                ->orderable(false)
                ->searchable(false),

            Column::make('start_date')
                ->title('Fecha de inicio')
                ->name('projects.start_date'),

            Column::make('end_date')
                ->title('Fecha de fin')
                ->name('projects.end_date'),

            Column::make('status_badge')
                ->title('Estado')
                ->name('projects.status'),

            Column::computed('actions')
                ->title('')
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->addClass('text-end'),
        ];
    }

    /**
     * Nombre del archivo Excel/PDF si posteriormente
     * agregamos Buttons.
     */
    protected function filename(): string
    {
        return 'Proyectos_' . date('YmdHis');
    }
}