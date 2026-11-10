

document.addEventListener('DOMContentLoaded', function () {

    const table = $('#projects-table').DataTable();

    const activitys = $('#activity-table').DataTable();

    /*
    |--------------------------------------------------------------------------
    | Búsqueda general
    |--------------------------------------------------------------------------
    */

    $('#project-search').on('keyup', function () {
        table.search(this.value).draw();
    });


    /*
    |--------------------------------------------------------------------------
    | Filtro por estado
    |--------------------------------------------------------------------------
    */

    $('#filter-status').on('change', function () {

        table
            .column('status:name')
            .search(this.value)
            .draw();

    });


    /*
    |--------------------------------------------------------------------------
    | Filtro por tipo
    |--------------------------------------------------------------------------
    */

    $('#filter-type').on('change', function () {

        table
            .column('type:name')
            .search(this.value)
            .draw();

    });


    /*
    |--------------------------------------------------------------------------
    | Filtro por fecha
    |--------------------------------------------------------------------------
    */

    $('#filter-start-date').on('change', function () {

        table
            .column('start_date:name')
            .search(this.value)
            .draw();

    });


    /*
    |--------------------------------------------------------------------------
    | Limpiar filtros
    |--------------------------------------------------------------------------
    */

    $('#btn-clear-filters').on('click', function () {

        $('#project-search').val('');
        $('#filter-status').val('');
        $('#filter-type').val('');
        $('#filter-start-date').val('');

        table
            .search('')
            .columns()
            .search('')
            .draw();

    });


    /*
    |--------------------------------------------------------------------------
    | Actualizar
    |--------------------------------------------------------------------------
    */

    $('#btn-refresh-projects').on('click', function () {

        table.ajax.reload(null, false);

    });





   const chartElement = document.querySelector('#projectProgressChart');



    if (!chartElement) {
        return;
    }

    const progress = Math.max(
        0,
        Math.min(
            100,
            Number(chartElement.dataset.progress ?? 0)
        )
    );

    const options = {
        series: [progress, 100 - progress],

        chart: {
            type: 'radialBar',
            width: 92,
            height: 92,
            sparkline: {
                enabled: true
            }
        },

        legend: {
            show: false
        },

        colors: ['#198754', '#e9ecef'],

        stroke: {
                lineCap: 'round'
            },

        plotOptions: {
            radialBar: {
                hollow: {
                    size: '65%'
                },

                dataLabels: {
                    name: {
                        show: false
                    },

                    value: {
                        show: true,
                        fontSize: '18px',
                        fontWeight: 600,
                        formatter: function (value) {
                            return `${value}%`;
                        }
                    }
                }
            }
        },


        
    };

    const chart = new ApexCharts(chartElement, options);
    chart.render();

  

// const options = {
//     series: [72],

//     chart: {
//         height: 200,
//         type: 'radialBar',
//     },


//     plotOptions: {
//         radialBar: {
//             hollow: {
//                 size: '65%',
//             },

//             dataLabels: {
//                 name: {
//                     show: true,
//                     fontSize: '16px',
//                 },

//                 value: {
//                     show: true,
//                     fontSize: '32px',
//                     fontWeight: 600,
//                     formatter: function (value) {
//                         return `${value}%`;
//                     },
//                 },
//             },
//         },
//     },

//     colors: ['#198754'],

//     stroke: {
//         lineCap: 'round',
//     },
// };

// if (chartElement) {
//     const chart = new ApexCharts(chartElement, options);

//     chart.render();
// }

});

