@section('content')

<x-adminlte-modal
    id="modalExample"
    title="Mi modal"
    theme="ligth"
    size="lg"
    scrollable
    staticBackdrop
>

    <x-slot name="title">
        <div class="d-flex align-items-center gap-2">
            <img
                src="{{ asset('images/security-icon.svg') }}"
                alt="Teléfono"
                style="width: 50px; height: 50px;"
            >

            <span>Mi modal</span>
        </div>
    </x-slot>

    <x-slot name="slot">
        <div id="panel-spinner-activation" class="panel-spinner d-none">
            <div class="loader"></div>
        </div>
        <div class="row justify-content-center ">
            <x-adminlte-card class="col-md-6 bg-white border borde-izquierdo">
                <x-slot title="slot">
                    <div class="steps-timeline">

                        <article class="step step-active" id="step1">
                            <span class="step-number">1</span>

                            <div class="step-content pt-2">
                            <h3>Escanea el código QR</h3>
                            <p>Abre tu aplicación de autenticación<br>y escanea el código</p>
                            </div>
                        </article>

                        <article class="step" id="step2">
                            <span class="step-number">2</span>

                            <div class="step-content pt-2">
                            <h3>Ingresa el código</h3>
                            <p>Copia el código de 6 dígitos<br>generado en la app</p>
                            </div>
                        </article>

                        <article class="step" id="step3">
                            <span class="step-number">3</span>

                            <div class="step-content pt-2">
                            <h3>Verificación</h3>
                            <p>Confirma tu identidad y<br>accede a tu cuenta</p>
                            </div>
                        </article>
                        </div>

                        <div class="security-tip mt-3">
                            <i class=" "></i>

                            <div>
                            <h4>Consejo</h4>
                            <p>El código cambia cada 30 segundos.<br>Si expira, genera uno nuevo.</p>
                        </div>
                    </div>
                </x-slot>   

            </x-adminlte-card>

            <x-adminlte-card class="col-md-6 bg-white border borde-derecho text-center" >
                <x-slot title="slot" bodyclass="px-2">
                        <h6 class="mb-4 font-weight-bold text-center" style="font-size: 16px; color: #171d46;">
                            Escanea este código QR con tu aplicación
                        </h6>

                        <div class="qr-wrapper mb-4 p-4 border bg-white" style="border-radius: 12px">
                            <span>
                                {!! auth()->user()->twoFactorQrCodeSvg() !!}
                            </span>
                        </div>

                        <div class="qr-help" style="color: #7c849e;font-size: 13px;">
                            <span>¿No puedes escanear?</span><br>

                            <a href="#" id="sendEmail" class="ml-2">Activar verificación por correo</a>

                        </div>
                </x-slot>
            </x-adminlte-card>

                     
        </div>
    </x-slot>


</x-adminlte-modal>

@stop