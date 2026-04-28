<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Error del servidor">
    <title>Forbbiden</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v6.0.0-beta2/css/all.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css" rel="stylesheet" />

    <style>
        .bg-login {
            background-image: url( {{ asset("./images/error/no_autorizacion.jpg") }} );
            background-repeat: no-repeat;
            background-position: center center;
            height: 100vh;
        }
    </style>
</head>

<body class="bg-login position-relative">
   
    <a onclick="window.history.back();" class="btn btn-back position-absolute" style="top: 75%; left: 50%; transform: translate(-50%, -50%); z-index: 10;">
        <i class="fa-solid fa-reply"></i> &nbsp;
        IR ATRÁS
    </a>

</body>

</html>


