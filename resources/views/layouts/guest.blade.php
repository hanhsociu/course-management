<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/toastr/toastr.min599c.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/custom-app.css') }}">
    @livewireStyles
</head>
<body>
<div class="se-pre-con"></div>
<div class="container-fluid p-0 home-content container-top-border">
    <div class="container">
        <nav class="navbar clearfix secondary-nav pt-0 pb-0 login-page-seperator" style="margin-top: 60px;">
            <ul class="list mt-0">
                <li><a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'active' : '' }}">Đăng nhập</a></li>
                <li><a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? 'active' : '' }}">Đăng ký</a></li>
            </ul>
        </nav>

        <div class="row mb-5">
            <div class="col-xl-6 col-lg-6 col-md-6 vertical-align d-none d-lg-block text-center">
                <div class="course-thumb-placeholder mx-auto" style="max-width: 400px; height: 400px; border-radius: 8px;">
                    <i class="fa fa-graduation-cap" style="font-size: 5rem;"></i>
                </div>
            </div>
            <div class="col-xl-6 offset-xl-0 col-lg-6 offset-lg-0 col-md-8 offset-md-2">
                <div class="rightRegisterForm">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <p class="text-center pb-4">
            <a href="{{ route('catalog') }}" class="forgot-text">&larr; Về trang chủ</a>
        </p>
    </div>
</div>

<script src="{{ asset('frontend/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('vendor/toastr/toastr.min599c.js') }}"></script>
<script>
$(window).on('load', function () { $('.se-pre-con').fadeOut('slow'); });
</script>
@livewireScripts
</body>
</html>
