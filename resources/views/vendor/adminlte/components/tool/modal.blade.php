{{-- <div class="modal fade t1-modal" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}-label" aria-hidden="true"
     @if ($staticBackdrop) data-bs-backdrop="static" data-bs-keyboard="false" @endif>
    <div class="{{ $dialogClass() }}">
        <div class="modal-content">
            <div class="modal-header {{ $theme ? 'text-bg-'.$theme : '' }}">
                <h5 class="modal-title" id="{{ $id }}-label">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            @isset($footer)
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div> --}}



<div
    class="modal fade t1-modal"
    id="{{ $id }}"
    tabindex="-1"
    aria-labelledby="{{ $id }}-label"
    aria-hidden="true"
    @if ($staticBackdrop)
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    @endif
>
    <div class="{{ $dialogClass() }}">
        <div class="modal-content">

            {{-- ENCABEZADO --}}
            <div class="modal-header">
                <div class="t1-modal-heading">

                    <div class="t1-modal-icon">
                        <i class="{{ $icon ?? 'fas fa-folder-open' }}"></i>
                    </div>

                    <div class="t1-modal-title-container">
                        <h5
                            class="modal-title"
                            id="{{ $id }}-label"
                        >
                            {{ $title }}
                        </h5>

                        @if (!empty($subtitle))
                            <p class="t1-modal-subtitle">
                                {{ $subtitle }}
                            </p>
                        @endif
                    </div>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar"
                ></button>
            </div>

            {{-- CONTENIDO --}}
            <div class="modal-body">
                {{ $slot }}
            </div>

            {{-- PIE --}}
            @isset($footer)
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endisset

        </div>
    </div>
</div>
