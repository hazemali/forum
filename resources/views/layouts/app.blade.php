<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.app = {!!

        json_encode([
            'csrfToken' => csrf_token(),
            'signedIn' => auth()->check(),
            'user' => auth()->check() ?  auth()->user()->toJson() : null,
            'policies' => auth()->check() ?  auth()->user()->policies->toJson() : []
        ]) !!};
    </script>


    <style>
        body {
            padding-bottom: 100px;
        }

        .level {
            display: flex;
            align-items: center;
        }

        .flex {
            flex: 1;
        }

        .mr-1 {
            margin-right: 1em;
        }

        [v-clock] {
            display: none;
        }

    </style>

    @yield('head')
</head>
<body>
<div id="app">

    @include('layouts.nav')
    @yield('content')


    <flash message="{{ session('flash') }}"></flash>


    <flash errormessage="{{ session('flashError') }}"></flash>

</div>


<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
