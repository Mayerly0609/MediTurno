@extends('adminlte::page')
@vite(['resources/js/app.js','resources/js/two-factor.js', 'resources/css/app.css'])

@section('title', 'Autenticación de dos factores')

@push('css')
    <style>
        #modalExample .btn-close {
            display: none;
        }
    </style>
@endpush




@if ($mostrarModal2FA)


    <div class="modal fade" id="modal2FA" tabindex="-1" aria-labelledby="modal2FALabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg ">
            <div class="modal-content">

                <div class="modal-header justify-content-center">
                    <h5 id="modal-activacion-mfa-label" class="modal-title"><div class="d-flex align-items-center justify-content-center">
                    <img class="mr-2" src="{{ asset('images/security-icon.svg') }}" width="50" height="50" alt="Simolo Seguridad">
                    <span><span class="font-weight-bold page-title"> Activar Verificación de Doble Factor </span><br> 
                    <small class="form-text text-muted text-sm page-subtitle"> Protege tu cuenta ingresando el codigo generado</small></span>

            
                </div> </h5>

                </div>

                <div class="modal-body">
                    <div id="panel-spinner-activation" class="panel-spinner d-none">
                        <div class="loader"></div>
                    </div>
                    <div class="row justify-content-center ">
                        <section class="verification-steps borde-izquierdo bg-white card border col-md-6">
                            <div class="card-body px-5 ">
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
                            </div>
                        </section>
                        <section class="verification-steps borde-derecho bg-white card d-flex flex-column 
                                align-items-center justify-content-center border text-center col-md-6">
                            <div class="card-body px-5">

                                <h6 class="mb-4 font-weight-bold" style="font-size: 16px; color: #171d46;">
                                    Escanea este código QR con tu aplicación
                                </h6>

                                <div class="qr-wrapper mb-4 p-4 border bg-white"   style=" border-radius: 12px">
                                    {!! auth()->user()->twoFactorQrCodeSvg() !!}
                                    <!-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=190x180&amp;data=otpauth%3A%2F%2Ftotp%2FPlataforma%2520Solicitudes%3Amayerly.arango%2540busscar.com.co%3Fsecret%3DX7BGELLYCQRI5IA2YGKDTFO5BNHTDNFW%26issuer%3DPlataforma%2520Solicitudes%26algorithm%3DSHA1%26digits%3D6%26period%3D30" alt="QR Code">                                             -->
                                </div>

                                <div class="qr-help" style="color: #7c849e;font-size: 13px;">
                                    <span>¿No puedes escanear?</span><br>

                                    <a  id="sendEmail" class="ml-2 cursor-pointer" >Activar verificación por correo</a>

                            </div>
                        </div></section>
                    </div>

                    <section class="card border" style="border-radius: 16px;">
                        <div class="card-body">
                            <div class=" justify-content-center ">
                                <!-- <img src="{{ asset('images/verification-phone.svg') }}" alt=""> -->

                                
                                
                                <form method="POST" action="{{ route('two-factor.email.verify') }}" id="formConfirmar2FA" class="d-flex justify-content-center">
                                    @csrf
                                    
                                    <input type="hidden" name="purpose" value="activation" id="purpose">
                                    <input type="hidden" name="code" id="otp-code">
                                     
                                <img src="{{ asset('images/phone.svg') }}" alt="Phone">

                                <div class=" text-center align-items-center flex-column">
                                    <div class="fw-bold ">Ingresa el código de 6 digitos <br>
                                        
                                        <small class="form-text text-muted text-sm page-subtitle fw-normal"> 
                                            Copia el codigo generado en tu aplicación de autenticación
                                        </small>
    
                                    </div>
                                    <div class="mt-4 mb-4">

                                        @for ($i = 1; $i <= 6; $i++)
                                            <input
                                                required
                                                maxlength="1"
                                                type="text"
                                                inputmode="numeric"
                                                autocomplete="{{ $i === 1 ? 'one-time-code' : 'off' }}"
                                                class="otp-input-verification"
                                                id="otp-input{{ $i }}">
                                        @endfor
    
    
                                        {{-- <div class="inputContainer">
                                            <input required="required" maxlength="1" name="otp[]" type="text" inputmode="numeric" autocomplete="one-time-code" class="otp-input-verification" id="otp-input1">
                                            <input required="required" maxlength="1" name="otp[]" type="text" inputmode="numeric" autocomplete="one-time-code" class="otp-input-verification" id="otp-input2">
                                            <input required="required" maxlength="1" name="otp[]" type="text" inputmode="numeric" autocomplete="one-time-code" class="otp-input-verification" id="otp-input3">
                                            <input required="required" maxlength="1" name="otp[]" type="text" inputmode="numeric" autocomplete="one-time-code" class="otp-input-verification" id="otp-input4"> 
                                            <input required="required" maxlength="1" name="otp[]" type="text" inputmode="numeric" autocomplete="one-time-code" class="otp-input-verification" id="otp-input5"> 
                                            <input required="required" maxlength="1" name="otp[]" type="text" inputmode="numeric" autocomplete="one-time-code" class="otp-input-verification" id="otp-input6"> 
                                        </div> --}}
                                    </div>
                                    <div class="col-md-12 ">
                                        <button type="submit" class="btn btn-block text-white" style="background: linear-gradient(135deg, #2255ed 0%, #7039df 100%);" id="verify-mfa-button">Verificar Código</button>
                                    </div>
                                </div>
                            </form>
                            
                            </div>
                        </di>
                            
                    </section>
                </div>

                <!-- <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Más tarde
                    </button>

                    <a href="{{ route('two-factor.enable') }}"
                       class="btn btn-primary">
                        Activar doble autenticación
                    </a>

                </div> -->

            </div>
        </div>
    </div>

    
@endif



@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const modalElement = document.getElementById('modal2FA');


            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        });
    </script>
@endpush
