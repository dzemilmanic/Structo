<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Laravel Vite CSS/JS --}}
    @vite(['resources/js/app.js'])
</head>
<body>
    <div class="page-wrapper">
        {{-- Navigacija --}}
        <nav class="navbar navbar-expand-lg navbar-dark mb-4">
            <div class="container">
                <a class="navbar-brand" href="/">Structo</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link" href="/">Početna</a></li>
                        <li class="nav-item"><a class="nav-link" href="/o-nama">O nama</a></li>
                        <li class="nav-item"><a class="nav-link" href="/kontakt">Kontakt</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        {{-- Glavni sadržaj --}}
        <div class="container">
            @yield('content')
        </div>
    </div>

    {{-- Footer --}}
    <footer class="text-center">
        <p>&copy; 2025 Moja Aplikacija</p>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>