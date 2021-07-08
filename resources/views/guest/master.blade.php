<!DOCTYPE html>
<html class="no-js" lang="{{ app()->getLocale() }}">
<head>

    @include('partials.meta-tags')
    @include('guest.partials.meta-tags')

    @yield('css')

    @include('guest.partials.styles')

</head>
<body class="theme--body">

    <div id="app">

        @yield('content')

        {{-- Dialogs --}}
        {{-- <dialog-container></dialog-container> --}}

    </div>

    @include('partials.script-tags')

</body>
</html>