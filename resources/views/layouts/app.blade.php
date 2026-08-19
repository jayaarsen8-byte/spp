<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Saudara Plafon PVC</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    @stack('styles')
</head>
<body>
    <div class="app-container">
        @auth
            @include('layouts.sidebar')
        @endauth
        <div class="app-main">
            @auth
                @include('layouts.topbar')
            @endauth
            <main class="app-content">
                @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
