<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>


    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500" rel="stylesheet">

    <!-- Stylesheets -->

    <link href="{{ asset('assets/frontend/css/bootstrap.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/frontend/css/swiper.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/frontend/css/ionicons.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">

    @stack('css')

</head>
<body>

@include('layouts.frontend.partial.header')

@yield('content')

@include('layouts.frontend.partial.footer', with($categories))


<!-- SCIPTS -->

<script src="https://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>

<script src="https://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>

<script src="{{ asset('assets/frontend/js/jquery-3.1.1.min.js') }}"></script>

<script src="{{ asset('assets/frontend/js/tether.min.js') }}"></script>

<script src="{{ asset('assets/frontend/js/bootstrap.js') }}"></script>

@stack('js')

<script src="{{ asset('assets/frontend/js/scripts.js') }}"></script>
{!! Toastr::message() !!}
<script>
    @if($errors->any())
    @foreach($errors->all() as $error)
    toastr.error('{{ $error }}', 'Error', {
        'closeButton':'true',
        'progressBar':'true'
    });
    @endforeach
    @endif
</script>

</body>
</html>
