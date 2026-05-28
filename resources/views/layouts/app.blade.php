<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Course Management') }}</title>

    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/fancybox.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/toastr/toastr.min599c.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/custom-app.css') }}">
    @livewireStyles
    @stack('styles')
</head>
<body class="@stack('body-class')">
<div class="se-pre-con"></div>

<nav class="navbar navbar-default fixed-top">
    <div class="row" style="flex-grow: 1;">
        <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2" id="logo">
            <i class="fa fa-bars d-inline-block d-md-none mobile-nav" aria-hidden="true"></i>
            <a href="{{ route('catalog') }}" class="float-xl-right ulearn-brand">
                <span class="ulearn-brand-text">{{ config('app.name', 'ULEARN') }}</span>
            </a>
        </div>
        <div class="col-md-6 col-lg-6 col-xl-6 d-none d-md-block text-center">
            <ul class="list-inline mb-0 pt-2 ulearn-top-nav">
                <li class="list-inline-item">
                    <a href="{{ route('catalog') }}" class="{{ request()->routeIs('catalog') ? 'active' : '' }}">Khóa học</a>
                </li>
                @auth
                    @if(auth()->user()->isAdmin())
                    <li class="list-inline-item">
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') || request()->routeIs('admin.*') ? 'active' : '' }}">Quản trị</a>
                    </li>
                    @else
                    <li class="list-inline-item">
                        <a href="{{ route('my-courses') }}" class="{{ request()->routeIs('my-courses') ? 'active' : '' }}">Khóa học của tôi</a>
                    </li>
                    @endif
                @endauth
            </ul>
        </div>
        <div class="col-6 col-sm-8 col-md-3 col-lg-4 col-xl-4">
            @guest
            <a class="btn btn-learna float-right" href="{{ route('login') }}">Đăng nhập / Đăng ký</a>
            @else
            <div class="dropdown float-right">
                <span id="dropdownMenuButtonRight" data-toggle="dropdown" class="ulearn-user-trigger">
                    {{ auth()->user()->name }} &nbsp;<i class="fa fa-caret-down"></i>
                </span>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButtonRight">
                    <a class="dropdown-item" href="{{ route('profile') }}">
                        <i class="fa fa-user"></i> Hồ sơ
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item border-0 bg-transparent w-100 text-left">
                            <i class="fa fa-sign-out-alt"></i> Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
            @endguest
        </div>
    </div>
</nav>

<div id="sidebar" class="d-md-none">
    <ul>
        <li><a href="javascript:void(0)" class="sidebar-title">Menu</a></li>
        <li><a href="{{ route('catalog') }}"><i class="fa fa-book category-menu-icon"></i> Khóa học</a></li>
        @auth
            @if(auth()->user()->isAdmin())
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-cog category-menu-icon"></i> Quản trị</a></li>
            @else
            <li><a href="{{ route('my-courses') }}"><i class="fa fa-graduation-cap category-menu-icon"></i> Khóa học của tôi</a></li>
            @endif
            <li><a href="{{ route('profile') }}"><i class="fa fa-user category-menu-icon"></i> Hồ sơ</a></li>
        @else
            <li><a href="{{ route('login') }}"><i class="fa fa-sign-in-alt category-menu-icon"></i> Đăng nhập</a></li>
            <li><a href="{{ route('register') }}"><i class="fa fa-user-plus category-menu-icon"></i> Đăng ký</a></li>
        @endauth
    </ul>
</div>

@if (isset($header))
<header class="ulearn-page-header">
    <div class="container">
        {{ $header }}
    </div>
</header>
@endif

<main>
    {{ $slot }}
</main>

<footer id="main-footer">
    <div class="row m-0">
        <div class="col-lg-4 col-md-6 mt-3">
            <ul>
                <li class="mb-1"><b>Liên kết</b></li>
                <li><a href="{{ route('catalog') }}">Danh mục khóa học</a></li>
                @auth
                <li><a href="{{ route('my-courses') }}">Khóa học của tôi</a></li>
                @else
                <li><a href="{{ route('login') }}">Đăng nhập</a></li>
                <li><a href="{{ route('register') }}">Đăng ký</a></li>
                @endauth
            </ul>
        </div>
        <div class="col-lg-8 col-md-6 text-center mt-4">
            <span class="ulearn-brand-text ulearn-brand-text--footer">{{ config('app.name', 'ULEARN') }}</span>
            <br>
            <span id="c-copyright">
                &copy; {{ date('Y') }} {{ config('app.name') }}. Học trực tuyến — thanh toán PayOS.
            </span>
        </div>
    </div>
</footer>

<script src="{{ asset('frontend/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('frontend/js/fancybox.min.js') }}"></script>
<script src="{{ asset('frontend/js/modernizr.js') }}"></script>
<script src="{{ asset('vendor/toastr/toastr.min599c.js') }}"></script>
<script>
$(window).on('load', function () {
    $('.se-pre-con').fadeOut('slow');
});
$(document).ready(function () {
    toastr.options.closeButton = true;
    toastr.options.timeOut = 5000;
    @if(session('success') || session('message'))
        toastr.success(@json(session('success') ?? session('message')));
    @endif
    @if(session('status'))
        toastr.success(@json(session('status')));
    @endif
    @if(session('error'))
        toastr.error(@json(session('error')));
    @endif
    @if(session('info'))
        toastr.info(@json(session('info')));
    @endif

    $('.mobile-nav').click(function () {
        $('#sidebar').toggleClass('active');
        $(this).toggleClass('fa-bars fa-times');
    });
});
</script>
@livewireScripts
@stack('scripts')
</body>
</html>
