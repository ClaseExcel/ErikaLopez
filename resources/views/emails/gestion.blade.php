<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Acta de reunión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #444;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: auto;
        }
        .header {
            background-color: #232222;
            color: #fff;
            padding: 15px;
            text-align: center;
            border-radius: 6px 6px 0 0;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #e2e2e2;
            border-top: none;
            border-radius: 0 0 6px 6px;
        }
        .footer {
            text-align: center;
            font-size: 0.9em;
            margin-top: 30px;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Acta de Reunión</h2>
        </div>
        <div class="content">
            <p>Estimado(a) {{ $gestion->cliente->razon_social }},</p>

            <p>Se ha generado una nueva acta de reunión correspondiente a la gestión con fecha <strong>{{ \Carbon\Carbon::parse($gestion->fecha_visita)->format('d/m/Y') }}</strong>.</p>

            @if (!empty($observacion))
                <p><strong>Observación:</strong></p>
                <p>{{ $observacion }}</p>
            @endif

            <p>Adjunto a este correo encontrará el acta en formato PDF.</p>

            <p>Gracias por su atención.</p>

            <p>
                Este correo es exclusivamente informativo. <br>
                Saludos, Erika Lopez.
            </p> <br><br>
        </div>

        <div class="footer">
            &copy; <small>
                <b>AVISO DE CONFIDENCIALIDAD:</b> Este correo electrónico contiene información confidencial y sólo puede ser
                utilizada
                por la persona o compañía a la cual está dirigido. Si no es el destinatario de este correo y lo recibió por
                error comuníquelo de inmediato, respondiendo al remitente y eliminando cualquier copia que pueda tener del
                mismo. Si no es el destinatario, no podrá usar su contenido, de hacerlo podría tener consecuencias legales como
                las contenidas en la Ley 1273 del 5 de enero de 2009 y todas las que le apliquen. Si es el destinatario, le
                corresponde mantener reserva en general sobre la información de este mensaje, sus documentos y/o archivos
                adjuntos, a no ser que exista una autorización explícita.
        
                Antes de imprimir este correo, considere si es realmente necesario hacerlo, recuerde que puede guardarlo como un
                archivo digital.
        
                <b>CONFIDENTIALITY NOTICE:</b> This e-mail contains confidential information and may only be used by the person
                or
                company to whom it is addressed. If you are not the intended recipient of this email and you received it in
                error, please notify the sender immediately by replying to the sender and deleting any copies you may have of
                the email. If you are not the addressee, you may not use its contents, and doing so may have legal consequences
                such as those contained in Law 1273 of January 5, 2009 and all those that apply to you. If you are the
                addressee, it is your responsibility to keep the information in this message, its documents and/or attached
                files confidential, unless you have explicit authorization.
        
                Before printing this email, consider whether it is really necessary to do so, remember that you can save it as a
                digital file.
            </small>.
        </div>
    </div>
</body>
</html>
