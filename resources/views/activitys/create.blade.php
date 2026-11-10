

<form action="{{ route('activities.store', $project) }}" method="POST">

    @csrf

    <section class="t1-modal-section">

        <div class="t1-section-heading">
            <i class="fas fa-info-circle"></i>
            <span>Información básica</span>
        </div>

        <div class="row g-3">

            {{-- Título --}}
            <div class="">
                <label for="title" class="form-label">
                    Título de la actividad <span class="text-danger">*</span>
                </label>

                <div class="t1-input-icon">

                    <i class="fa-regular fa-rectangle-list"></i>
                    <input type="text"
                           id="title"
                           name="title"
                           class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title') }}"
                           placeholder="Ej. Validación de configuración de firewall"
                           maxlength="255"
                           required>
    
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Descripción --}}
            <div class="">
                <label for="description" class="form-label">
                    Descripción <span class="text-danger">*</span>
                </label>

                <div class="t1-input-icon">
                    <i class="fas fa-pen"></i>
                    <textarea id="description"
                              name="description"
                              class="form-control @error('description') is-invalid @enderror"
                              rows="3"
                              maxlength="1000"
                              placeholder="Describe la actividad, objetivos, entregables o criterios de aceptación..."
                              required>{{ old('description') }}</textarea>
    
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </section>

    {{-- SECCIÓN 2: RESPONSABLE Y FECHAS --}}
    <section class="t1-modal-section">

        <div class="t1-section-heading">
            <i class="fas fa-user-clock"></i>
            <span>Responsable</span>
        </div>

        <div class="row ">

            <div class="row">

                {{-- Responsable --}}
                <div class="col-md-6 ">

                    <div class="mb-3">
                        <label for="responsible_id" class="form-label">
                            Responsable <span class="text-danger">*</span>
                        </label>
    
                        <div class="t1-input-icon">
    
                            <i class="fas fa-user"></i>
        
                            <select id="responsible_id"
                                    name="responsible_id"
                                    class="form-select @error('responsible_id') is-invalid @enderror"
                                    required>
                                <option value="">Selecciona un responsable</option>
        
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        @selected(old('responsible_id') == $user->id)>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
        
                            @error('responsible_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Fase --}}
                    <div class="mb-3">
                        <label for="phase_id" class="form-label">
                            Fase del proyecto <span class="text-danger">*</span>
                        </label>

                        <select id="phase_id"
                                name="phase_id"
                                class="form-select @error('phase_id') is-invalid @enderror"
                                required>
                            <option value="">Selecciona una fase</option>

                            @foreach ($phases as $phase)
                                <option value="{{ $phase->id }}"
                                    @selected(old('phase_id') == $phase->id)>
                                    {{ $phase->order }}. {{ $phase->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('phase_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if ($phases->isEmpty())
                            <small class="text-danger">
                                Este proyecto todavía no tiene fases registradas.
                            </small>
                        @endif
                    </div>

                </div>

                <div class="col-md-6 ">

                    {{-- Prioridad --}}
                    <div class="mb-3 col-md-12">
                        <label for="priority" class="form-label">
                            Prioridad <span class="text-danger">*</span>
                        </label>
    
                        <select id="priority"
                                name="priority"
                                class="form-select @error('priority') is-invalid @enderror"
                                required>
                            <option value="high"
                                @selected(old('priority', 'medium') == 'high')>
                                <i class="bi bi-circle-fill"></i> Alta
                            </option>
    
                            <option value="medium"
                                @selected(old('priority', 'medium') == 'medium')>
                                🟡 Media
                            </option>
    
                            <option value="low"
                                @selected(old('priority', 'medium') == 'low')>
                                🟢 Baja
                            </option>
                        </select>
    
                        @error('priority')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <div class="d-flex gap-2">

                        {{-- Fecha límite --}}
                        <div class="mb-3 col-md-6" >
                            <label for="due_date" class="form-label">
                                Fecha límite <span class="text-danger">*</span>
                            </label>
    
                            <x-adminlte-input-flatpickr
                                name="due_date"
                                required
                                id="due_date"
                                class="form-control @error('due_date') is-invalid @enderror"
                                value="{{ old('due_date') }}"
                            />
        
        
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
        
                        {{-- Tiempo estimado --}}
                        <div class="mb-3 col-md-6">
                            <label for="estimated_hours" class="form-label">
                                Tiempo estimado (horas)
                            </label>
        
                            <input type="number"
                                   id="estimated_hours"
                                   name="estimated_hours"
                                   class="form-control @error('estimated_hours') is-invalid @enderror"
                                   value="{{ old('estimated_hours') }}"
                                   min="0.5"
                                   step="0.5"
                                   placeholder="Ej. 8">
        
                            @error('estimated_hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>

           
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
            id="btnGuardarActividad"
        >
            <i class="fas fa-save me-2"></i>
            Guardar Actividad
        </button>
    </div>


</form>
