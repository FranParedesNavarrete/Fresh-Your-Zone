<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" href="{{ asset('/assets/images/logo/fzy-logo-dark.png') }}" type="image/png">
    <script src="https://www.paypal.com/sdk/js?client-id=ASsabQjL310r0hUZq_D2zDFREmaOMR7Z-KzoYYq7A65PMQQvNAk5jvcqt0TY23v5LJ2DgCtaGx8Xm1z7&currency=EUR"></script>
    <title>@yield('title')</title>
</head>
<body class="lightmode">
    @include('partials.header')

    <main>
        @yield('content')
    </main>

    <div class="darkmodeBtn" onclick="darkmodeTogle()">
        <i class="bi bi-moon-fill" id="darkmodeIcon"></i>
    </div>

    @include('partials.footer')
</body>

<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/orders.js') }}"></script>
<script src="{{ asset('js/paypal.js') }}"></script>
</html>
