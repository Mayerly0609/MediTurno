
@extends('adminlte::auth.auth-master', ['authType' => 'login'])
@vite(['resources/js/app.js','resources/js/two-factor.js', 'resources/css/app.css'])

@section('auth_body')


    <p class="login-box-msg">Bienvenidos a la Plataforma de Logistica Interna

    <form action="{{ route('two-factor-login.email.verify') }}" method="POST" id="formConfirmar2FA">
        @csrf
        <input type="hidden" name="purpose" value="login" id="purpose">
        <input type="hidden" name="code" id="otp-code">

        <section class="" style="border-radius: 16px;">
            <div id="panel-spinner-activation" class="panel-spinner d-none" >
                <div class="loader"></div>
            </div>
            <div class="row justify-content-center ">
                <img src="{{ asset('images/verification-phone.svg') }}" alt=""  style="width: 38%;">
            
                <div class=" text-center align-items-center d-flex flex-column">
                    <div class=" "> <span class="fs-6 fw-bold">Ingresa el código de 6 digitos <br></span>

                        <small  class="form-text text-muted text-sm"> 
                            Copia el codigo generado en tu aplicación de autenticación ó 
                            <a href="#" id="sendEmail" class="form-text text-sm text-bold text-primary">Solicita el código por correo</a>
                        </small>

                    </div>
                    <div class="mt-4 mb-4">


                        <div class="inputContainer">
                            <input required="required" maxlength="1"  name="otp[]" type="text" inputmode="numeric" autocomplete="one-time-code" class="otp-input-verification" id="otp-input1">
                            <input required="required" maxlength="1"  name="otp[]" type="text" inputmode="numeric" autocomplete="one-time-code" class="otp-input-verification" id="otp-input2">
                            <input required="required" maxlength="1"  name="otp[]" type="text" inputmode="numeric" autocomplete="one-time-code" class="otp-input-verification" id="otp-input3">
                            <input required="required" maxlength="1"  name="otp[]" type="text" inputmode="numeric" autocomplete="one-time-code" class="otp-input-verification" id="otp-input4"> 
                            <input required="required" maxlength="1"  name="otp[]" type="text" inputmode="numeric" autocomplete="one-time-code" class="otp-input-verification" id="otp-input5"> 
                            <input required="required" maxlength="1"  name="otp[]" type="text" inputmode="numeric" autocomplete="one-time-code" class="otp-input-verification" id="otp-input6"> 
                        </div>
                    </div>
                    <div class="w-75">
                        <button type="submit" class="btn btn-block text-white verifyButton" style="background: linear-gradient(135deg, #2255ed 0%, #7039df 100%);" id="verify-mfa-button">Verificar Código</button>
                        <!-- <button class="btn btn-block text-white"  id="verify-mfa-button">Verificar Código</button> -->
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                {{ $errors->first() }}
                            </div>
                        @endif
                    </div>
                    <p class="  Note"><a href="{{ route('login')}}" class="form-text text-sm text-bold">Volver</a></p>
                </div>

            </div>
            
        </section>
    </form>


    
    

   
@endsection

