@extends('adminlte::page')

@section('title', 'Importar Usuario LDAP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Gestión de Usuarios LDAP</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('controlUsers.index') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Importar LDAP</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="card card-primary card-outline mb-4">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-search me-1"></i> Buscar Usuario en Active Directory</h3>
        </div>
        <div class="card-body">
            <!-- Formulario de búsqueda -->
            <div class="input-group mb-3">
                <input type="text" id="search_term" class="form-control" placeholder="Ingrese el username o nombre a buscar...">
                <button class="btn btn-primary" type="button" id="btn_search">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>

            <!-- Contenedor de Alertas -->
            <div id="alert_container"></div>

            <!-- Tabla de Resultados -->
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover align-middle" id="results_table" style="display:none;">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre Completo</th>
                            <th>Username</th>
                            <th>Correo</th>
                            <th>Rol RBAC</th>
                            <th>Acciones / Estado</th>
                        </tr>
                    </thead>
                    <tbody id="results_body"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnSearch = document.getElementById('btn_search');
        const searchTerm = document.getElementById('search_term');
        const resultsTable = document.getElementById('results_table');
        const resultsBody = document.getElementById('results_body');
        const alertContainer = document.getElementById('alert_container');

        // Búsqueda por tecla Enter
        searchTerm.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                btnSearch.click();
            }
        });

        // Evento Click de Búsqueda
        btnSearch.addEventListener('click', function () {
            const term = searchTerm.value.trim();
            if (term.length < 3) {
                showAlert('warning', 'Ingrese al menos 3 caracteres para buscar.');
                return;
            }

            btnSearch.disabled = true;
            btnSearch.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Buscando...';

            fetch(`{{ route('controlUsers.search') }}?term=${encodeURIComponent(term)}`)
                .then(response => response.json())
                .then(data => {
                    let rows = '';
                    if (data.length === 0) {
                        rows = '<tr><td colspan="5" class="text-center text-muted">No se encontraron usuarios coincidentes en Active Directory.</td></tr>';
                    } else {
                        data.forEach(user => {
                            let roleSelect = '';
                            let actionButton = '';

                            if (user.already_imported) {
                                const rolesBadge = user.roles.length > 0 
                                    ? user.roles.map(r => `<span class="badge bg-info text-dark me-1">${r}</span>`).join('') 
                                    : '<span class="badge bg-secondary">Sin Rol</span>';
                                
                                roleSelect = rolesBadge;
                                actionButton = `<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Ya Registrado</span>`;
                            } else {
                                roleSelect = `<select class="form-select form-select-sm" id="role_${user.username}">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>`;

                                actionButton = `<button class="btn btn-sm btn-success btn-import" data-username="${user.username}">
                                    <i class="bi bi-person-plus-fill me-1"></i> Importar
                                </button>`;
                            }

                            rows += `<tr>
                                <td><strong>${user.cn}</strong></td>
                                <td><code>${user.username}</code></td>
                                <td>${user.email}</td>
                                <td>${roleSelect}</td>
                                <td>${actionButton}</td>
                            </tr>`;
                        });
                    }

                    resultsBody.innerHTML = rows;
                    resultsTable.style.display = 'table';
                })
                .catch(error => {
                    showAlert('danger', 'Ocurrió un error al consultar el Active Directory.');
                })
                .finally(() => {
                    btnSearch.disabled = false;
                    btnSearch.innerHTML = '<i class="bi bi-search"></i> Buscar';
                });
        });

        // Evento para Importar (Delegación de Eventos)
        document.addEventListener('click', function (e) {
            if (e.target && e.target.closest('.btn-import')) {
                const btn = e.target.closest('.btn-import');
                const username = btn.getAttribute('data-username');
                const roleSelect = document.getElementById(`role_${username}`);
                const selectedRole = roleSelect ? roleSelect.value : null;

                if (!selectedRole) {
                    showAlert('warning', 'Seleccione un rol válido.');
                    return;
                }

                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Importando...';

                fetch("{{ route('controlUsers.import') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        username: username,
                        role: selectedRole
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        showAlert('success', data.message);
                        btnSearch.click(); // Refrescar tabla
                    } else {
                        showAlert('danger', data.message || 'Error al importar.');
                        btn.disabled = false;
                    }
                })
                .catch(() => {
                    showAlert('danger', 'Error de comunicación con el servidor.');
                    btn.disabled = false;
                });
            }
        });

        function showAlert(type, message) {
            alertContainer.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show mt-2" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
        }
    });
</script>
@stop