import $ from 'jquery';
window.$ = window.jQuery = $;

import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

import Swal from 'sweetalert2';
window.Swal = Swal;

import ApexCharts from 'apexcharts';
window.ApexCharts = ApexCharts;

// Yajra DataTables
import 'laravel-datatables-vite';


import select2 from 'select2';
import 'select2/dist/css/select2.min.css';
import 'select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css';
select2($);


import './administration.js';
import 'admin-lte/dist/css/adminlte-colors.css';


// console.log(document.documentElement.getAttribute('data-bs-theme'))
// document.documentElement.setAttribute('data-bs-theme', 'light');

// console.log(document)

// // import { TabulatorFull as Tabulator } from 'tabulator-tables'
// // window.Tabulator = Tabulator;

if($(document).hasClass('.datepicker')){

    flatpickr('.datepicker', {
        dateFormat: 'd/m/Y',
        locale: 'es',
    });
}



// $(document).ready(function () {
//     $('#cliente').select2({
//         theme: 'bootstrap-5',
//         placeholder: 'Seleccione una opción',
//         allowClear: true,
//         width: '100%',
//         minimumInputLength: 0,
        
//         ajax: {
//             url: '/getRoles',
//             type: 'GET',
//             dataType: 'json',
//             delay: 250,
            
//             data: function(params) {
//                 return {
//                     search: params.term || ''
//                 };
//             },
            
//             processResults: function(data, params) {
//                 params.page = params.page || 1;
                
//                 return {
//                     results: data.results || [],
//                     pagination: {
//                         more: false
//                     }
//                 };
//             },
            
//             cache: false
//         },
        
//         templateResult: function(data) {
//             if (data.loading) {
//                 return 'Buscando...';
//             }
//             return data.text || '';
//         },
        
//         templateSelection: function(data) {
//             return data.text || '';
//         }
//     });
    
//     $('#cliente').on('change', function() {
//         console.log('Seleccionado:', $(this).select2('data'));
//     });
// });

import 'jstree';
import 'jstree/dist/themes/default/style.min.css';

$(document).ready(function () {
            $('#jstree-container').jstree({
                'core' : {
                    'data' : {
                        'url' : '/getFamilias',
                        'dataType' : 'json'
                    }
                },
                'check_callback' : true,
                'plugins' : ['wholerow', 'checkbox','search'],
                'checkbox': {
                    'three_state': false,
                    'tie_selection' : true

                },
                'search': {
                    'case_insensitive': true,
                    'show_only_matches': true
                }
            });
        });

// new TomSelect('#usuarios', {
//     plugins: ['remove_button','clear_button'],
//     placeholder : 'Seleccione un usuario',
//     create: false,
//     valueField: 'id',
//     labelField: 'name',
//     searchField: 'name',
//     preload: true,
//     load: function(query, callback) {
//         fetch(`${urlBuscarUsuarios}?q=${encodeURIComponent(query)}`)
//             .then(response => response.json())
//             .then(data => {
//                 console.log('Datos recibidos:', data);
//                 callback(data)
//             })
//             .catch(error => {
//                 console.error(error);
//                 callback();
//             });
//     }
// });