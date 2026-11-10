<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Layout;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    /**
     * Construcción del DataTable.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id');
    }

    /**
     * Consulta que alimentará el DataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Configuración HTML y JavaScript.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->responsive(true)
            ->layout(function (Layout $layout) {
                $layout->topStart('buttons');
                $layout->topEnd('search');
                $layout->bottomStart('info');
                $layout->bottomEnd('paging');
            })

            ->orderBy(0)

            ->buttons([
                Button::make('copy'),
                Button::make('csv'),
                Button::make('excel'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    /**
     * Columnas de la tabla.
     */
    protected function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),

            Column::make('name')
                ->title('Nombre'),

            Column::make('email')
                ->title('Correo electrónico'),

            Column::make('created_at')
                ->title('Fecha creación'),
        ];
    }

    /**
     * Nombre del archivo exportado.
     */
    protected function filename(): string
    {
        return 'Usuarios_' . date('YmdHis');
    }
}