<?php

namespace App\DataTables;

use App\Models\Action;
use App\Models\AdminlteAction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ActionsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Action> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('actions', function (AdminlteAction $action) {
                return '
                    <button
                        type="button"
                        class="btn btn-md btn-outline-warning btn-editar"
                        data-id="' . $action->id . '"
                        title="Editar"
                    >
                        <i class="fas fa-pencil"></i>
                    </button>

                    <button
                        type="button"
                        class="btn btn-md btn-outline-danger btn-eliminar"
                        data-id="' . $action->id . '"
                        title="Eliminar"
                    >
                        <i class="fas fa-trash "></i>
                    </button>
                ';
            })
            ->rawColumns(['actions']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Action>
     */
    public function query(AdminlteAction $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('actions-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1)
                    ->parameters([
                        'language' => [
                            'url' => asset('vendor/datatables/es-ES.json'),
                        ],
                    ]);
                    ;
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('module')->title('Modulo'),
            Column::make('route')->title('Ruta'),
            Column::computed('actions')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Actions_' . date('YmdHis');
    }
}
