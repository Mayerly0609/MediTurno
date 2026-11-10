<?php

namespace App\DataTables;

use App\Models\Projects\ProjectActivity;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProjectActivitiesDataTable extends DataTable
{

    protected int $projectId;

    public function __construct(int $projectId)
    {
        $this->projectId = $projectId;
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable {

        return (new EloquentDataTable($query))

            ->addColumn(
                'responsible',
                function ($activity) {

                    return $activity->responsible?->name
                        ?? 'Sin asignar';

                }
            )

            ->addColumn(
                'phase',
                function ($activity) {

                    return $activity->project_phase?->name
                        ?? '-';

                }
            )

            ->editColumn(
                'status',
                function ($activity) {

                    return match ($activity->status) {

                        'completed' =>
                            '<div class="badges">
                                <button class="green">Completada</button>
                            </div>',

                        'in_progress' =>
                            '<div class="badges">
                                <button class="blue">En Progreso</button>
                            </div>',
                            

                        'pending' =>
                            '<div class="badges">
                                <button class="secondary">Pendiente</button>
                            </div>',

                        'cancelled' =>
                            '<div class="badges">
                                <button class="danger">Cancelada</button>
                            </div>',

                        default =>
                            '<span class="badge bg-secondary">'
                            . e($activity->status) .
                            '</span>',
                    };

                }
            )

            ->editColumn('priority',function ($activity) {

                    return match ($activity->priority) {

                        'high' =>
                            '<div class="badges">
                                <button class="red">Alta</button>
                            </div>'
                            ,
                        'medium' =>
                            '<div class="badges">
                                <button class="yellow">Media</button>
                            </div>',

                        'low' =>
                            '<div class="badges">
                                <button class="green">Baja</button>
                            </div>',

                        default =>
                            e($activity->priority),
                    };

                }
            )

            ->editColumn('due_date',function ($activity) {

                    return $activity->due_date
                        ? $activity->due_date->format('d M Y')
                        : '-';

                }
            )

            ->addColumn(
                'actions',
                function ($activity) {

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
                                    href="' . route('activities.show', $activity) . '">
                                    <i class="bi bi-eye me-2"></i>
                                    Ver Actividad
                                </a>
                            </li>

                            <li>
                                <a
                                    class="dropdown-item"
                                    href="' . route('activities.edit', $activity) . '">
                                    <i class="bi bi-pencil me-2"></i>
                                    Editar
                                </a>
                            </li>


                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <button
                                    type="button"
                                    class="dropdown-item text-danger btn-delete-project"
                                    data-id="' . $activity->id . '">

                                    <i class="bi bi-trash me-2"></i>
                                    Eliminar
                                </button>
                            </li>

                        </ul>
                    </div>
                ';

                }
            )

            ->rawColumns([
                'status',
                'priority',
                'actions',
            ])

            ->setRowId(
                'id'
            );
    }


    public function query(ProjectActivity $model): QueryBuilder {

        return $model
            ->newQuery()
            ->with([
                'responsible',
                'project_phase',
            ])
            ->where(
                'project_id',
                $this->projectId
            );
    }


    public function html(): HtmlBuilder
    {
        return $this->builder()

            ->setTableId('activity-table')

            ->columns($this->getColumns())

            ->minifiedAjax()
            ->responsive(false)
            ->autoWidth(true)

            ->pageLength(10)
            ->searching(true)
            
            ->orderBy(4,'asc')
            
            
            ->lengthChange(false)
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
                'pageLength' => 10,
                
                // 'layout' => ['topStart' => 'buttons', 'topEnd' => null, 'top2Start' => 'search', 'bottomStart' => 'info', 'bottomEnd' => 'paging'],
                'dom' => "<'row '<'col-md-4'><'col-md-4'><'col-md-4'f>>" .
                            "<'row'<'col-12'tr>>" .
                            "<'row mt-1'<'col-md-5 page-subtitle  'i><'col-md-7'p>>",
                'language' => [
                    'processing' => '
                        <div class="dt-processing">
                            <div class="spinner-border spinner-border-sm text-primary"
                                 role="status">
                            </div>
                            <span>Cargando Actividades...</span>
                        </div>
                    ',

                    'search' => '',
                    'searchPlaceholder' => 'Buscar Actividades ...',

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

                    // 'paginate' => [
                    //     'first' => 'Primero',
                    //     'last' => 'Último',
                    //     'next' => 'Siguiente',
                    //     'previous' => 'Anterior',
                    // ],
                ],
            ]);
    }


    public function getColumns(): array
    {
        return [

            Column::make('title')->title('Actividad')->addClass('text-center'),

            Column::make('phase')->title('Fase')->orderable(false)->addClass('text-center'),

            Column::make('responsible')
                ->title('Responsable')
                ->orderable(false)->addClass('text-center'),

            Column::make('status')
                ->title('Estado')
                ->orderable(false)->addClass('text-center'),

            Column::make('priority')
                ->title('Prioridad')
                ->orderable(false)->addClass('text-center'),

            Column::make('due_date')
                ->title('Fecha límite'),

            Column::computed('actions')
                ->title('Acciones')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->orderable(false)
                ->width(100)
                ->addClass('text-center'),
        ];
    }


    protected function filename(): string
    {
        return 'ProjectActivities_' .
            date('YmdHis');
    }
}
