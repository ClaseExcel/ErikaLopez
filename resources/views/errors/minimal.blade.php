<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{ asset('../images/logos/favicon.png') }}">
    <title>@yield('title')</title>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <style>

        html,
        body {
            background-color: #f4f4f4;
            color: #7b7e82;
            font-family: Roboto;
            height: 100vh;
            margin: 0;
        }

        .contenedor {
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
        }
        h4{
            font-size: 1.7rem;
            margin: 0;
            margin-bottom: 10px;
            text-align: center;
        }
        h5{
            margin: 0;
            text-align: center;
        }

        /* animar robot  */
        #robot {
            animation: mover .8s infinite alternate;
        }

        @keyframes mover {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(10px);
            }
        }

        .btn-back {
            color: #7b7e82;
            background-color: #f4f4f4;
            cursor: pointer;
        }

        .btn {
            display: inline-block;
            font-weight: 400;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
                border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .btn-back:hover {
            color: #565d64;
            background-color: #f4f4f4;
        }
    </style>
</head>

<body>

    <div class="contenedor">
        <div class="contenido">
            <div id="robot" style="text-align: center;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="150" height="150">
                    <path fill="#7b7e82"
                        d="M320 0c17.7 0 32 14.3 32 32l0 64 120 0c39.8 0 72 32.2 72 72l0 272c0 39.8-32.2 72-72 72l-304 0c-39.8 0-72-32.2-72-72l0-272c0-39.8 32.2-72 72-72l120 0 0-64c0-17.7 14.3-32 32-32zM208 384c-8.8 0-16 7.2-16 16s7.2 16 16 16l32 0c8.8 0 16-7.2 16-16s-7.2-16-16-16l-32 0zm96 0c-8.8 0-16 7.2-16 16s7.2 16 16 16l32 0c8.8 0 16-7.2 16-16s-7.2-16-16-16l-32 0zm96 0c-8.8 0-16 7.2-16 16s7.2 16 16 16l32 0c8.8 0 16-7.2 16-16s-7.2-16-16-16l-32 0zM264 256a40 40 0 1 0 -80 0 40 40 0 1 0 80 0zm152 40a40 40 0 1 0 0-80 40 40 0 1 0 0 80zM48 224l16 0 0 192-16 0c-26.5 0-48-21.5-48-48l0-96c0-26.5 21.5-48 48-48zm544 0c26.5 0 48 21.5 48 48l0 96c0 26.5-21.5 48-48 48l-16 0 0-192 16 0z" />
                </svg>
            </div>
            <h4>
               @yield('message')
            </h4>
            <h5>
                @yield('btn-back')
            </h5>
        </div>
    </div>

</body>

</html>