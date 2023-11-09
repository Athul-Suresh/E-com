@php
use App\Models\Menu;
    $menu_item = Menu::where('is_active', true)->get();
    $usr = Auth::guard('admin')->user();
    // dd(Auth::guard('admin')->user());

@endphp
<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin')</title>

    {{-- @include('admin.dashboard.partials.seo') --}}

    @include('admin.dashboard.layouts.partials.styles')
    @yield('styles')
</head>

<body>
    {{-- <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div> --}}
    <div id="main-wrapper">


        @include('admin.dashboard.layouts.commons.header')
        @include('admin.dashboard.layouts.commons.sidebar')
        <div class="content-body">
        @yield('admin-content')
        </div>

        @include('admin.dashboard.layouts.commons.footer')
    </div>
    @include('admin.dashboard.layouts.partials.scripts')
    @yield('scripts')
</body>

</html>
