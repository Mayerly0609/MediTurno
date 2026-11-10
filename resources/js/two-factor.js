
document.addEventListener('DOMContentLoaded', function () {

    $("#sendEmail").on('click', function (event) {
        event.preventDefault();

        let url = $("#purpose").val() == 'login' ? `/two-factor-login/email/send` : `/two-factor/email/send`;

        $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: {_token: $('meta[name="csrf-token"]').attr('content'), purpose: $("#purpose").val()},
            beforeSend: function () {
                $('#panel-spinner-activation').removeClass('d-none');
            },
            success: function (data) {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        text: data.message,
                        confirmButtonText: 'Aceptar'
                    });

                    // Cambiar la pantalla para que el usuario
                    // pueda validar el código recibido por correo.
                    // $('#formConfirmar2FA')
                    //     .attr('action', "{{ route('two-factor.email.verify') }}");

                    $('.page-subtitle').text(
                        'Ingresa el código de seis dígitos enviado a tu correo.'
                    );
                } else {
                    Swal.fire({
                        icon: 'error',
                        text: data.message || 'No fue posible enviar el código.'
                    });
                }
            },
            error: function(xhr){
                const mensaje = xhr.responseJSON?.message || 'Ocurrió un error al enviar el código.';

                Swal.fire({
                    icon: 'error',
                    text: mensaje
                });
            },
            complete: function () {
                $('#panel-spinner-activation').addClass('d-none');
            }
        });
    });

    const otpInputs = document.querySelectorAll('.otp-input-verification');

    otpInputs.forEach((input, index) => {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 1);

            if (this.value && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }

            $("#step2").addClass("step-active");


        });

        input.addEventListener('keydown', function (event) {
            if (event.key === 'Backspace' && !this.value && index > 0) {
                otpInputs[index - 1].focus();
            }
            $("#step2").addClass("step-active");

        });

        input.addEventListener('paste', function (event) {
            const digits = event.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
            $("#step2").addClass("step-active");

            if (!digits) return;
            event.preventDefault();

            digits.split('').forEach((digit, position) => {
                if (otpInputs[index + position]) {
                    otpInputs[index + position].value = digit;
                }
            });

            const nextIndex = Math.min(index + digits.length, otpInputs.length - 1);
            otpInputs[nextIndex].focus();
        });
    });

    $('#formConfirmar2FA').on('submit', function (event) {
        const codigo = Array.from(
            document.querySelectorAll('.otp-input-verification')
        ).map(input => input.value).join('');

        if (!/^\d{6}$/.test(codigo)) {
            event.preventDefault();

            Swal.fire({
                icon: 'warning',
                text: 'Debes ingresar los seis dígitos del código.'
            });

            return;
        }

        document.getElementById('otp-code').value = codigo;
    });

});
