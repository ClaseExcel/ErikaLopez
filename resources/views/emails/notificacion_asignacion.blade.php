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
        <h2 style="color:#232222;">¡Se te ha asignado una actividad!</h2>
        <p style="color:#616161;">Te notificamos que se te ha asignado la actividad con nombre registrado <b>{{ $nombre_actividad }}</b> con fecha limite del 
        <b>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $fecha)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}</b> a solicitud del cliente <b> {{ $empresa }} </b> para que se de su desarrollo.</p>

    </div><br>

    <small>
        Este correo es exclusivamente informativo. <br>
        Saludos, Erika Lopez.
    </small><br><br>
</body>

</html>
