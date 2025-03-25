<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            margin-bottom: 20px;
        }

        header .company-info {
            margin-left: 20px;
        }

        header img {
            max-width: 100px;
        }

        header h1 {
            font-size: 24px;
            margin: 0;
        }

        header p {
            font-size: 14px;
            color: #777;
            margin: 5px 0;
        }

        .title {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .section {
            margin-bottom: 20px;
        }

        .label {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }

        /* Ensure consistent display for print */
        @media print {
            body {
                margin: 0;
            }

            .container {
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <!--<img src="logo.png" alt="Company Logo">-->
            <div class="company-info">
                <p><strong>{{ env('APP_NAME') }}</strong></p>
                <p><strong>{{ __('Address') }}:</strong> {{ env('EMPRESA_REFERENCIA') }},
                    {{ env('EMPRESA_CIUDAD') }},
                    {{ env('EMPRESA_ESTADO') }}, {{ env('EMPRESA_PAIS') }}</p>
                <p>
                    @if (env('EMPRESA_CORREO'))
                        {{ env('EMPRESA_CORREO') }} |
                    @endif
                    @if (env('EMPRESA_TELEFONO'))
                        {{ env('EMPRESA_TELEFONO') }}
                    @endif
                </p>
            </div>
        </header>
        @yield('content')
        <footer style="position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 12px;">
            <p>&copy; <?php echo date('Y'); ?> {{ env('ORIGEN') }}. {{ __('All rights reserved') }}.</p>
        </footer>
    </div>
</body>

</html>
