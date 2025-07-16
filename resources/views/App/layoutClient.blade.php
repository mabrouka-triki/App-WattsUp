<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'WattsUp')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Security-Policy" content="
    default-src 'self'; 
    script-src 'self' https://cdn.jsdelivr.net 'unsafe-inline'; 
    style-src 'self' https://cdn.jsdelivr.net 'unsafe-inline'; 
    font-src 'self' https://cdn.jsdelivr.net data:; 
    img-src 'self' data: https://cdn.jsdelivr.net;
">
<!-- CSS Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" 
      integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" 
      crossorigin="anonymous">

<!-- JS Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" 
        crossorigin="anonymous"></script>
    {{-- Styles spécifiques à l’app --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    {{-- Hook pour ajouter des <style> ou <link> depuis une vue enfant --}}
    @yield('head')
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('assets/WattsUp.svg') }}"
                     alt="WattsUp Logo"
                     style="height: 50px; margin-right: 10px;">
                WattsUp
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#main-navbar" aria-controls="main-navbar"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="main-navbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/Client') }}">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('') }}">Add facture</a></li>

                    @auth
                        @if(Auth::user()->role === 'admin')
                            <li class="nav-item"><a class="nav-link" href="{{ url('/admin') }}">Details</a></li>
                        @endif
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link btn btn-outline-secondary">Déconnexion</button>
                            </form>
                        </li>
                    @endauth

                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Connexion</a></li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="container py-4">
        @yield('content')
    </main>

    {{-- Bootstrap Bundle (JS + Popper) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-PuCQgOthnYMp2WEJlU7s4ZAkD3e6yE/OuJIQv3CXhYYFkf9AM7EA2zdXnT4o0S0M"
            crossorigin="anonymous"></script>

    {{-- Pour injecter des scripts spécifiques depuis une vue enfant :
         @push('scripts') ... @endpush --}}
    @stack('scripts')
</body>
</html>
