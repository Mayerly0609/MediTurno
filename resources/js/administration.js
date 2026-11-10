
// --------------------------------------------------- ACCIONES -----------------------------------------------------
// Nueva fila 
$(document).on('click','#btnNuevaAccion', function () {
    addFirstRow();
});

function addFirstRow(){
    const table = $('#actions-table').DataTable();

    // Evitar crear más de una fila nueva
    if ($('#actions-table tbody .fila-nueva').length > 0) {
        return;
    }

    const nuevaFila = `
        <tr class="fila-nueva">
            <td></td>
            <td>
                <input type="text" name="module" class="form-control form-control-sm input-module">
            </td>
            
            <td>
                <input type="text" name="route" class="form-control form-control-sm input-route">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-md btn-outline-success btn-guardar-nuevo">
                    <i class="fas fa-save"></i>
                </button>
            </td>
        </tr>
    `;

    $('#actions-table tbody').prepend(nuevaFila);
}

// Guardar nuevo
$(document).on('click', '.btn-guardar-nuevo', function () {

    const button = $(this);
    const row = button.closest('tr');

    const module = row.find('.input-module').val().trim();
    const route = row.find('.input-route').val().trim();

    // Limpiar errores anteriores
    row.find('.is-invalid').removeClass('is-invalid');
    row.find('.invalid-feedback').remove();

    // Deshabilitar botón mientras se procesa
    button.prop('disabled', true);

    $.ajax({
        url: '/admin/actions',
        type: 'POST',

        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),

            module: module,
            route: route,
        },

        success: function (response) {
            // Mostrar mensaje de éxito
            alert(response.message);

            // Recargar DataTable manteniendo la página actual
            $('#actions-table').DataTable().ajax.reload(function(){}, false);
        },

        error: function (xhr) {

            button.prop('disabled', false);

            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                console.log(xhr)

                $.each(errors, function (field, messages) {

                    const input = row.find('.input-' + field);

                    if (input.length) {
                        // Marcar input
                        input.addClass('is-invalid');

                        // Mostrar mensaje
                        input.after(`
                            <div class="invalid-feedback">
                                ${messages[0]}
                            </div>
                        `);
                    }
                });
            }
            
            return;
        }
    });

});

// Guardar fila 
$(document).on('click', '.btn-guardar-edicion', function () {

    const button = $(this);
    const row = button.closest('tr');
    const id = button.data('id');

    const module = row.find('.edit-module').val();
    const route = row.find('.edit-route').val();

    const url = window.routes.actionsUpdate.replace('__ID__', id);
    $.ajax({
        url: url,
        type: 'PUT',

        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            module: module,
            route: route
        },

        success: function (response) {

            alert(response.message);
            $('#actions-table').DataTable().ajax.reload(function(){}, false);

        },

        error: function (xhr) {

            console.error(xhr);

            alert('No fue posible actualizar la acción.');
        }
    });
});

// eliminar fila 
$(document).on('click', '.btn-eliminar', function () {

    const button = $(this);
    const id = button.data('id');

    if (!confirm('¿Está seguro de eliminar esta acción?')) {
        return;
    }
    const url = window.routes.actionsDelete.replace('__ID__', id);

    $.ajax({
        url: url,
        type: 'DELETE',

        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },

        success: function (response) {

            alert(response.message);

            $('#actions-table').DataTable().ajax.reload(function(){}, false);

        },

        error: function (xhr) {

            console.error(xhr);

            alert('No fue posible eliminar la acción.');
        }
    });
});

// editar fila 
$(document).on('click', '.btn-editar', function () {

    const button = $(this);
    const row = button.closest('tr');

    const id = button.data('id');

    // Evitar editar dos veces la misma fila
    if (row.hasClass('fila-editando')) {
        return;
    }

    row.addClass('fila-editando');

    const module = row.find('td:eq(1)').text().trim();
    const route = row.find('td:eq(2)').text().trim();
  
    row.find('td:eq(1)').html(`
        <input type="text"
               class="form-control form-control-sm edit-module"
               value="${module}">
    `);

    row.find('td:eq(2)').html(`
        <input type="text"
               class="form-control form-control-sm edit-route"
               value="${route}">
    `);

    row.find('td:eq(3)').html(`
        <button type="button"
                class="btn btn-md btn-outline-success btn-guardar-edicion"
                data-id="${id}">
            <i class="fas fa-save"></i>
        </button>

        <button type="button"
                class="btn btn-md btn-outline-secondary btn-cancelar-edicion">
            <i class="fas fa-times"></i>
        </button>
    `);
});

// cancelar edición 
$(document).on('click', '.btn-cancelar-edicion', function () {

    $('#actions-table').DataTable().ajax.reload(function(){}, false);
});

// ------------------------------------------------------------------------------------------------------------------
// --------------------------------------------------- PERMISOS -----------------------------------------------------
$(document).on('click','.permission-item', function () {
    $('.permission-item').removeClass('bg-black/8');

    const permission = $(this).data('id');


    $(this).addClass('bg-black/8')

    $.ajax({
        url: `/admin/permissions/getActions/${permission}`,
        type: 'GET',
        success: function (response) {

            $('.action-checkbox').prop('checked', false);

            response.actions.forEach(function (actionId) {

                $(`.action-checkbox[value="${actionId}"]`).prop('checked', true);

            });

        }
    });
});



$(document).ready(function () {

    let selectedRegister = null;


    /* =========================================================
       SELECCIONAR REGISTRO
    ========================================================= */

    $('#listaRegistros').on('click', '.registro-item', function () {

        $('.registro-item').removeClass('active');

        $(this).addClass('active');

        selectedRegister = $(this).data('id');

        var url = $('#listaRegistros').data('url');

        cargarInformacion(url, selectedRegister);

    });


    /* =========================================================
       CARGAR REGISTRO
    ========================================================= */

    function cargarInformacion(url, register) {

        $('#emptyRegister').hide();
        $('#registerContent').show();
        $('#btnSaveActions').prop('disabled', true);

        $('.registro-checkbox').prop('checked', false);

        $.ajax({

            url: `${url}${register}`,
            type: 'GET',
            dataType: 'json',
            success: function (response) {

                $('.registro-checkbox').prop('checked', false);
                response.ids.forEach(function (id) {

                    $(`#registro_${id}`).prop('checked', true);

                });

                actualizarContador();

                $('#btnSaveActions').prop('disabled', false);

            },

            error: function (xhr) {

                console.error(
                    'Error cargando las acciones:',
                    xhr.responseText
                );

            }

        });


        /*
         * Obtener información del permiso
         */

        let item = $(`.registro-item[data-id="${register}"]`);

        let name = item.find('.registro-name').text().trim();

        let description = item.find('.registro-description').text().trim();


        $('#selectedRegisterName').text(name);

        $('#selectedRegisterDescription').text(description);

    }


    /* =========================================================
       CHECKBOX
    ========================================================= */

    $('#actionsContainer').on('change','.registro-checkbox',function () {
            actualizarContador();
        }
    );


    /* =========================================================
       ACTUALIZAR CONTADOR
    ========================================================= */

    function actualizarContador() {

        let selected = $('.registro-checkbox:checked').length;

        $('#footerSelectedCount').text(
            selected === 1
                ? '1 acción seleccionada'
                : `${selected} acciones seleccionadas`
        );

    }


    /* =========================================================
       BUSCAR PERMISOS
    ========================================================= */

    $('#searchRegister').on('keyup', function () {

        let search = $(this).val().toLowerCase();

        $('.registro-item').each(function () {
            
            let text = $(this).data('search').toLowerCase();
            
            $(this).toggle(text.includes(search));

        });

    });


    /* =========================================================
       BUSCAR ACCIONES
    ========================================================= */

    $('#searchRegisterDisp').on('keyup', function () {

        let search = $(this)
            .val()
            .toLowerCase();


        $('.registro-disponible').each(function () {

            let text = $(this).data('search').toLowerCase();


            $(this).toggle(
                text.includes(search)
            );

        });
    });


    /* =========================================================
       SUBMIT
    ========================================================= */
    
    $('#formAsignacion').on('submit', function (event) {
        
        if (!selectedRegister) {
            event.preventDefault();
            alert('Selecciona primero un permiso.');
            return;
        }
        
        var origen =$('#formAsignacion').data('form');
        
        /*
        * La acción se define dinámicamente.
        *
        * Ejemplo:
        *
        * /admin/permissions/asignActions/11
        */
       
       if(origen == "roles"){
           $(this).attr('action',`/admin/roles/asignPermissions/${selectedRegister}`);
           
        }else{
            $(this).attr('action',`/admin/permissions/asignActions/${selectedRegister}`);
            
        }
        
    });
    
    /* =========================================================
       NUEVO PERMISO
    ========================================================= */

    $('#nuevoRegistro').on('click', function () {

        let modalId = $(this).data('modal');

        let modalElement = document.getElementById(modalId);
        let modal = bootstrap.Modal.getOrCreateInstance(modalElement);

        modal.show();

        

    });

    /* =========================================================
       EDITAR PERMISO
    ========================================================= */

    $('#listaRegistros').on('click','.btn-edit',function (event) {

        event.stopPropagation();
        
            let id = $(this).data('id');
            
            let name = $(this).data('name');
            
            let description = $(this).data('description');
            
            let origen = $(this).data('origen');
            
            console.log(origen);
            let modalElement;
            // Datos
            $('#name').val(name);

            $('#label').val(description);

            if(origen == "roles"){
                // Método Laravel
                $('#methodRol').val('PUT');

                // URL
                $('#formRoles').attr(
                    'action',
                    `/admin/roles/${id}`
                );
    
    
                // Título
                $('#modalNuevoRol')
                    .find('.modal-title')
                    .text('Editar Rol');
    
                // Botón
                $('#btnGuardarRol').html('<i class="fas fa-save mr-1"></i> Guardar cambios');
    
    
                // Mostrar modal
                modalElement = document.getElementById("modalNuevoRol");
            }else{
                 // Método Laravel
                $('#methodPermission').val('PUT');

                // URL
                $('#formPermission').attr(
                    'action',
                    `/admin/permissions/${id}`
                );
    
                // Título
                $('#modalNuevoPermiso')
                    .find('.modal-title')
                    .text('Editar permiso');
    
                // Botón
                $('#btnGuardarPermiso').html('<i class="fas fa-save mr-1"></i> Guardar cambios');
    
                // Mostrar modal
                modalElement = document.getElementById("modalNuevoPermiso");
            }

            let modal = bootstrap.Modal.getOrCreateInstance(modalElement);

            modal.show();

        }
    );
});
