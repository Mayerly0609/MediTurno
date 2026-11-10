
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de doble factor</title>
</head>
<body style="margin:0;padding:0;font-family:Arial,Helvetica,sans-serif;color:#172554;">

    
    <div class="container">
        <table style="margin:0;padding:0;font-family:Arial,Helvetica,sans-serif;color:#172554;" role="presentation" width="100%">
            <tr>
                <td align="center">
                    <table width="640" cellspacing="0" cellpadding="0" border="0">
                        <tbody>
                            <tr>
                                <td style="font-size: 24px; font-family: Arial, Helvetica, sans-serif; color: #000; text-align: left; font-weight: bolder;" colspan="3" width="680">
                                    <img src="{{ $message->embed(public_path('images/logobusscar.png')) }}" width="200" alt="Logo Busscar">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
                        style="max-width:620px;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 3px 14px rgba(15,23,42,.08);">
                        
                        @if ($purpose == 'activation')
                            <tr>
                                <td style="padding:32px 36px 12px;">
                                    <h1 style="margin:0;font-size:22px;line-height:28px;color:#172554;">
                                        Verificación de doble factor
                                    </h1>
                                    <p style="margin:10px 0 0;font-size:14px;line-height:21px;color:#64748b;">
                                        Hola, hemos recibido una solicitud para activar la autenticación de doble factor en la plataforma de <b>Logistica Interna</b>
                                    </p>
                                </td>
                            </tr>
                        @endif
        
                        <tr>
                        <td style="padding:20px 36px 32px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
                            style="background:#f7f7ff;border:1px solid #eceeff;border-radius:10px;">
                            <tr>
                                <td width="120" align="center" valign="middle" style="padding:26px 12px;">
                                <div style="padding-top: 10px;text-align:center;border-radius:50%;background-color:rgb(192 203 247) !important;font-size:32px;">
                                    <img src="{{ $message->embed(public_path('images/verification-phone.png')) }}" alt="Verificación">
                                </div>
                                </td>
        
                                <td valign="middle" style="padding:24px 28px 24px 4px;">
                                <h2 style="margin:0 0 12px;font-size:17px;line-height:23px;color:#172554;">
        
                                    {{ $purpose != 'activation' ? 'Código de verificación' : '' }}
                                    
                                </h2>
        
                                <p style="margin:0 0 16px;font-size:13px;line-height:20px;color:#334155;">
                                    @if ($purpose)
                                        Usa el siguiente código para confirmar que eres tú y completar
                                        la activación de la autenticación de doble factor.
                                    @else
                                        Usa el siguiente código para confirmar que eres tú e iniciar
                                        sesión en la plataforma de <b>TI</b>.
                                    @endif
                                </p>
        
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                    <td style="padding:10px 25px;border:1px dashed #aab7ff;border-radius:6px;background:#ffffff;">
                                        <span style="font-size:30px;line-height:30px;font-weight:700;letter-spacing:6px;color:#3154f5; font-family: cursive">
                                        {{ $code}}
                                        </span>
                                    </td>
                                    </tr>
                                </table>
                                    <p style="margin:14px 0 0;font-size:12px;line-height:18px;color:#64748b;">
                                        Este código vence en 2 minutos.
                                    </p>
                                </td>
                            </tr>
                            </table>
                        </td>
                        </tr>
        
                        <tr>
                        <td align="center" style="padding:0 36px 10px;font-size:12px;line-height:18px;color:#94a3b8;">
        
                            <div style=" background: #e6ffe8 !important;border: 1px solid #7cf596 !important;border-radius: 12px;padding: 15px;
                                display: flex;align-items: center;" >
                                <div class="icon mr-5 ml-3" style=" font-size: 50px;">🛡️</div>
                                <div style="display: flex;flex-direction: column;text-align: justify;">
                                    <strong style="font-family: Arial, Helvetica, sans-serif; font-size:15px; color: #00c54c !important;"> Importante </strong>
                                    <p style="font-size:12px;line-height:18px;color:#64748b;">Este código es personal e intransferible. No lo compartas con nadie.</p>
                                    <p style="margin:0 0 16px;font-size:13px;line-height:20px;color:#64748b;">Si tu no solicitaste activar el doble factor, por favor ignora este correo o <b>contacta a soporte de TI</b> </p>
                                </div>
                            </div>
        
                            
                        </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="center" style="padding:5px;font-size:11px;line-height:18px;color:#94a3b8;">
                    Plataforma TI · Busscar
                    <br>
                    Mensaje automático, por favor no respondas a este correo.
                </td>
            </tr>
        </table>
    </div>

</body>


</html>
