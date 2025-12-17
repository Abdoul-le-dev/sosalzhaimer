<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover, user-scalable=no">
    <meta name="theme-color" content="#0ea5e9">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0f172a">

    
    <title>@yield('title', 'App Aide Alzheimer')</title>
    
    {{-- CSS global --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
     <link rel="stylesheet" href="{{ asset('css/exo.css') }}">
    
    {{-- CSS modulaire --}}
    @stack('styles')
</head>
<body data-theme="{{ session('theme', 'dark') }}">
    
    <div class="app">
        {{-- Header avec SOS --}}
        @include('components.header')
        
        {{-- Contenu principal --}}
        <main id="content">
            @yield('content')
        </main>

        @yield('templates')
        
        {{-- Navigation bottom --}}
        @include('components.navigation')
    </div>
    
    {{-- Backdrop pour modales --}}
    <div class="backdrop" id="backdrop" onclick="closeAllSheets()"></div>
    
    {{-- Zone toasts --}}
    <div class="toasts"></div>
    
    {{-- JS global --}}
    <script src="{{ asset('js/modules/app.js') }}"></script>


    
    {{-- JS modulaire --}}
    @stack('scripts')
</body>
</html>