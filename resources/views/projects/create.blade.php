

<form action="{{ route('projects.store') }}" method="POST">

    @csrf

    <section class="t1-modal-section">

        <div class="t1-section-heading">
            <i class="fas fa-info-circle"></i>
            <span>Información básica</span>
        </div>

        <div class="row g-3">

            {{-- Nombre --}}
            <div class="col-md-8">
                <label for="name" class="form-label">
                    Nombre del proyecto
                    <span class="t1-required">*</span>
                </label>

                <div class="t1-input-icon"> <i class="fas fa-folder"></i>

                    <input
                        type="text"
                        class="form-control"
                        id="name"
                        name="name"
                        placeholder="Ej. Gestión de Órdenes de Compra"
                        maxlength="150"
                        value="{{ old('name') }}"
                    >
                </div>
            </div>

            {{-- Estado --}}
            <div class="col-md-4">
                <label for="status" class="form-label" >
                    Estado
                    <span class="t1-required">*</span>
                </label>

                <div class="t1-input-icon">
                    <i class="fas fa-list"></i>
                    <select
                        class="form-select " id="status"
                        name="status"
                        required
                    >
                        <option
                            value="planning"
                            @selected(old('status', 'planning') === 'planning')
                        >
                            Planificación
                        </option>
    
                        <option
                            value="active"
                            @selected(old('status') === 'active')
                        >
                            Activo
                        </option>
    
                        <option
                            value="on_hold"
                            @selected(old('status') === 'on_hold')
                        >
                            En pausa
                        </option>
    
                        <option
                            value="completed"
                            @selected(old('status') === 'completed')
                        >
                            Finalizado
                        </option>
                    </select>
                </div>

            </div>

            {{-- Descripción --}}
            <div class="col-12">
                <label
                    for="description"
                    class="form-label"
                >
                    Descripción
                </label>

                <div class="t1-input-icon">
                    <i class="fas fa-pen"></i>

                    <textarea
                        class="form-control"
                        id="description"
                        name="description"
                        rows="4"
                        maxlength="500"
                        placeholder="Describe el objetivo del proyecto..."
                    >{{ old('description') }}</textarea>
                </div>

               
            </div>

        </div>
    </section>

    {{-- SECCIÓN 2: RESPONSABLE Y FECHAS --}}
    <section class="t1-modal-section">

        <div class="t1-section-heading">
            <i class="fas fa-user-clock"></i>
            <span>Responsable y fechas</span>
        </div>

        <div class="row g-3">

            {{-- Líder --}}
            <div class="col-md-4">
                <label
                    for="leader_id"
                    class="form-label"
                >
                    Líder del proyecto
                    <span class="t1-required">*</span>
                </label>

                <div class="t1-input-icon">
                    <i class="fas fa-user"></i>

                    <select
                        class="form-select"
                        id="leader_id"
                        name="leader_id"
                    >
                        <option value="">
                            Selecciona un líder
                        </option>
    
                        @foreach($users as $leader)
                            <option
                                value="{{ $leader->id }}"
                                @selected((string) old('leader_id') === (string) $leader->id)
                            >
                                {{ $leader->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            {{-- Fecha inicial --}}
            <div class="col-md-4">
                <label
                    for="start_date"
                    class="form-label"
                >
                    Fecha de inicio
                </label>


                <x-adminlte-input-flatpickr
                    name="start_date"
                    id="start_date"
                    value="{{ old('start_date') }}"
                />

            </div>

            {{-- Fecha final --}}
            <div class="col-md-4">
                <label
                    for="end_date"
                    class="form-label"
                >
                    Fecha de finalización
                </label>

                <x-adminlte-input-flatpickr
                    name="end_date"
                    id="end_date"
                    value="{{ old('end_date') }}"
                />

                
            </div>

            {{-- Errores generales --}}
            @if($errors->any())
                <div class="col-12">
                    <div class="alert alert-danger mb-0">
                        <strong>
                            No se pudo guardar el proyecto.
                        </strong>

                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

        </div>
    </section>

    <div class=" modal-footer">

        <button
            type="button"
            class="btn btn-t1-secondary"
            data-bs-dismiss="modal"
        >
            Cancelar
        </button>
    
        <button
            type="submit"
            class="btn btn-t1-primary"
            id="btnGuardarProyecto"
        >
            <i class="fas fa-save me-2"></i>
            Guardar proyecto
        </button>
    </div>


</form>
