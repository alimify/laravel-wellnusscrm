<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- FAVICON Icon -->
    <link rel="icon" href="../../../../favicon.ico">

    <!-- Title -->
    <title>Leads Management</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{asset('css/admin/dashboard.css')}}" rel="stylesheet">

</head>

<body>
@include('layouts.admin.partials.topbar')

<div class="container-fluid">
    <div class="row">


        <main role="main">
            @include('layouts.admin.partials.notice')
            @yield('content')
        </main>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>


<!-- Icons -->

</body>
</html>
