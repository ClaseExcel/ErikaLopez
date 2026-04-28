<!doctype html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat" rel="stylesheet">

    <style>
        body {
            font-family: 'Montserrat', sans-serif !important;
        }

        .button {
            padding: 0.7em 2em;
            font-size: 16px;
            background: #222222;
            color: #fff;
            cursor: pointer;
            text-decoration: none;
            border-radius: 50px;
        }
    </style>
</head>

<body>
    <div style="display:flex; justify-content:center;">
        <img src="{{ asset('images/logos/logo_contable.png') }}" alt="" width="200px">
    </div>

    <div class="content" style="text-align:center;">
        <h2 style="color:#232222;">¡Se solicito un requerimiento!</h2>
        <p style="color:#616161;">Te notificamos que se te ha asignado el requerimiento <b>{{ $tipo_requerimiento }}</b>
            con consecutivo <b>{{ $consecutivo }}</b> a solicitud del cliente {{ $usuario_cliente }} para que se de su
            desarrollo.</p>

    </div><br>

    <small>
        Este correo es exclusivamente informativo. <br>
        Saludos, Erika Lopez
    </small><br><br>
    <small>
        <b>AVISO DE CONFIDENCIALIDAD:</b> Este correo electrónico contiene información de caracter confidencial. Si no
        es el destinatario de este correo y lo recibió por error comuníquelo de inmediato, respondiendo a
        info@erikalopez.com.co y eliminando cualquier copia que pueda tener del mismo. Si no es el destinatario, no podrá
        usar su contenido, de hacerlo podría tener consecuencias legales como las contenidas en la Ley 1273 del 5 de
        enero de 2009 y todas las que le apliquen. Si es el destinatario, le corresponde mantener reserva en general
        sobre la información de este mensaje, sus documentos y/o archivos adjuntos, a no ser que exista una autorización
        explícita. Antes de imprimir este correo, considere si es realmente necesario hacerlo, recuerde que puede
        guardarlo como un archivo digital.

    </small><br><br>
    <small>
        <b>CONFIDENTIALITY NOTICE:</b> This email contains confidential information. If you are not the intended
        recipient of this email and received it in error, please notify us immediately by responding to
        info@erikalopez.com.co and delete any copies you may have. If you are not the intended recipient, you are not
        allowed to use its content; doing so may have legal consequences as outlined in Law 1273 of January 5, 2009, and
        any applicable laws. If you are the intended recipient, you must maintain confidentiality regarding this
        message's information, documents, and/or attached files unless explicit authorization is given. Before printing
        this email, consider whether it is indispensable; remember that you can save it as a digital file.
    </small>
</body>

</html>
