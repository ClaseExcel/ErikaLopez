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
    <div style="background-color:#eaeaea;display:flex; justify-content:center; padding:20px;">
        <img src="{{ asset('images/logos/logo_contable.png') }}" alt="" width="200px">
    </div>

    <div class="content" style="margin:90px 90px 150px 90px;">
        Buen día
        <strong>{{ $actividad->empresa_asociada ? $actividad->empresa_asociada->razon_social : $actividad->cliente->razon_social }}</strong>:
        <br><br>

        Nos complace informarle que la actividad correspondiente a <strong>{{ $actividad->nombre }}</strong> ha sido
        finalizada exitosamente el
        el día
        <strong>{{ \Carbon\Carbon::parse($actividad->reporte_actividades->fecha_fin)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}</strong>.
        <br><br>

        Durante el proceso, se cumplieron todos los objetivos establecidos y se llevaron a cabo las tareas según lo
        programado. Si lo desea, podemos proporcionarle un resumen detallado de lo realizado o cualquier
        documentación
        complementaria. <br><br>

        Agradecemos su confianza en nuestros servicios y quedamos atentos a cualquier consulta o requerimiento
        adicional
        que pueda tener. <br><br>

        @if ($observaciones)
            A continuación dejamos algunas observaciones: <br><br>

            <p>{{ $observaciones }}</p> <br><br>
        @endif

        Saludos cordiales, <br>
        <strong><em>{{ Auth::user()->nombres . ' ' . Auth::user()->apellidos }}</em></strong> <br>
        erikalopez <br>
        {{ Auth::user()->email }}
    </div>
    
    <small>
        Este correo es exclusivamente informativo. <br>
        Saludos, Erika Lopez
    </small><br><br>
    <small>
        <b>AVISO DE CONFIDENCIALIDAD:</b> Este correo electrónico contiene información de caracter confidencial. Si no
        es el destinatario de este correo y lo recibió por error comuníquelo de inmediato, respondiendo a
        info@erikalopez.com.co y eliminando cualquier copia que pueda tener del mismo. Si no es el destinatario, no
        podrá
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
