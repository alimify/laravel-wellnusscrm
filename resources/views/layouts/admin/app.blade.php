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
    <title>@yield('title')</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{asset('css/admin/dashboard.css')}}" rel="stylesheet">

    <!-- CUSTOME CSS -->
    @stack('css')
</head>

<body>
@include('layouts.admin.partials.topbar')

<div class="container-fluid">
    <div class="row">

        @include('layouts.admin.partials.sidebar')

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        @include('layouts.admin.partials.notice')
        @yield('content')
    </main>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>


<!-- Icons -->
<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
<script>
    feather.replace()
</script>
@stack('script')
</body>
</html>
