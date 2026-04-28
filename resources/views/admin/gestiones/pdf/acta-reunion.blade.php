<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Acta de reunión</title>
    <style>
         @page {
            margin-left: 0;
            margin-right: 0;
            margin-bottom: 0;
        }

        body {
            font-family: "Open Sans", sans-serif;
            font-size: 14px;
            background-image: url('./images/background/background_pdf.jpg');
            background-size: 100% 100%;
            background-repeat: no-repeat;
            background-position: top left;
            background-attachment: fixed;
            height: 100vh;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header img {
            width: 100px;
            margin-bottom: 10px;
        }

        .header h1 {
            color: #232222;
            font-size: 28px;
            margin: 0;
        }

        .info {
            padding: 20px;
            margin-top: 20%;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .info p {
            margin: 8px 0;
        }

        .ckeditor-content {
            padding: 0%;
            margin: 0%;
            height: 80px;
            overflow: hidden;
            page-break-after: always;
        }
    </style>
</head>

<body>
    @php
        use App\Helpers\HtmlCleaner;
    @endphp

    <div class="header">
        <h1>ACTA DE REUNIÓN</h1>
    </div>

   
    <div class="info">
        <p><strong>Tipo de visita:</strong> {{ $gestion->tipo_visita }}</p>
        <p><strong>Fecha de la gestión:</strong> {{ $gestion->fecha_visita }}</p>
        <p><strong>Cliente:</strong> {{ $gestion->cliente->razon_social }}</p>
        <p><strong>Persona quien creó la gestión:</strong>
            {{ $gestion->usuario_create->nombres . ' ' . $gestion->usuario_create->apellidos }}</p>
        <p><strong>Última actualización:</strong>
            {{ \Carbon\Carbon::parse($gestion->updated_at)->format('d-m-Y h:i A') }}</p>
    </div>


    @if ($gestion->detalle_visita)
        <div style="text-align: center">
            <h3 style="color: #232222">Detalle de la gestión</h3>
        </div>

        <div style="display:flex;justify-content:center; text-align:justify;">
            <p>{!! HtmlCleaner::clean($gestion->detalle_visita) !!}</p>
        </div>
    @endif

    @if ($gestion->compromisos)
        <div style="text-align: center">
            <h3 style="color: #232222">Compromisos Erika Lopez</h3>

        </div>

        <div style="display:flex;justify-content:center; text-align:justify;">
            <p>{!! HtmlCleaner::clean($gestion->compromisos) !!}</p>
        </div>
    @endif


    @if ($gestion->compromisos_cliente)
        <div style="text-align: center">
            <h3 style="color: #232222">Compromisos por parte del cliente</h3>
        </div>

        <div style="display:flex;justify-content:center; text-align:justify;">
            <p>{!! HtmlCleaner::clean($gestion->compromisos_cliente) !!}</p>
        </div>
    @endif


    @if ($gestion->hallazgos)
        <div style="text-align: center">
            <h3 style="color: #232222">Observaciones</h3>
        </div>

        <div style="display:flex;justify-content:center; text-align:justify;">
            <p>{!! HtmlCleaner::clean($gestion->hallazgos) !!}</p>
        </div>
    @endif

</body>

</html>
