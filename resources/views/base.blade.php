<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        <!-- Jquery -->
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>

        <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        @yield('content')
    </body>
</html>
