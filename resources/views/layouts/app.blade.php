<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Software de contabilidad avanzado">
    <title>{{ env('APP_NAME', 'Erika Lopez') }} - Inicio</title>
    
    {{-- CDN --}}
    {{-- Bootstrap:  --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    {{-- Font Awesome: --}}
    <link href="https://use.fontawesome.com/releases/v6.0.0-beta2/css/all.css" rel="stylesheet" />
    {{-- iCheck Bootstrap:  --}}
    <link href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css" rel="stylesheet" />


    {{-- CUSTOM --}}
    <link href="{{ asset('css/adminltev3.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/custom-colors.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/custom-fonts.css') }}" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="{{ asset('../images/logos/favicon.png') }}">
    {{-- <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" /> --}}
    @yield('styles')

    <style>
        .bg-login{
            /* background-image: url( {{ asset('./images/background/bg-help.jpg') }} ) ; */
            background-image: url( {{ asset('./images/background/fondo_2026.jpg') }} );
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: #f8f8f8;
            padding: 10px;
        }
    </style>
    

</head>

<body class="header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden login-page bg-login" style="padding-right: 0 !important;">
    
    @yield('content')
    @yield('scripts')
</body>

</html>
