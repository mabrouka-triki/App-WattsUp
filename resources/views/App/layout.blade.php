<!-- resources/views/layouts/app.blade.php -->
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="
        default-src 'self';
        script-src 'self' https://cdn.jsdelivr.net/ https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js;
        style-src 'self' https://cdn.jsdelivr.net/ 'unsafe-inline';
        font-src 'self' https://cdn.jsdelivr.net/;
    ">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ url('') }}">
                <img src="{{ asset('assets/WattsUp.svg') }}" alt="WattsUp Logo" style="height: 50px; margin-right: 10px;">
                WattsUp
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-navbar"
                aria-controls="main-navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="main-navbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('') }}">Accueil</a>
                    </li>

                    @auth
                        @if(Auth::user()->role === 'admin')
                            {{-- Contenu pour admin, si nécessaire --}}
                        @endif
                    @endauth

                    @guest
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-primary me-2" href="{{ route('login') }}">Connexion</a>
                        </li>
                    @endguest

                    @auth
                        <li class="nav-item">
                            <span class="nav-link">Bonjour, {{ Auth::user()->name }}</span>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link btn btn-outline-secondary">Déconnexion</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="container py-4">
        @yield('content')
    </main>
</body>

</html>
