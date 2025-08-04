<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    
    {{-- Styles spécifiques à l'app --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    @yield('head')
</head>

<body>
    <!-- NAVBAR BOOTSTRAP PERSONNALISÉE -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <!-- Logo et Brand -->
   <a class="navbar-brand" href="{{ url('/') }}">
    <img src="{{ asset('assets/WattsUp.svg') }}" alt="WattsUp Logo" class="logo-corner"   style="height: 40px; margin-right: 10px;">
    
</a>



            <!-- Bouton burger pour mobile -->
            <button class="navbar-toggler" 
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#main-navbar" 
                    aria-controls="main-navbar"
                    aria-expanded="false" 
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Contenu de la navbar -->
            <div class="collapse navbar-collapse" id="main-navbar">
                <!-- Menu principal à gauche -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('Client') ? 'active' : '' }}" 
                           href="{{ url('/Client') }}">
                             Accueil
                        </a>
                    </li>
                    
                    @if(isset($compteur) && $compteur)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('Client.gestionFacture') ? 'active' : '' }}" 
                               href="{{ route('Client.gestionFacture', $compteur->id_compteur) }}">
                                Suivre la Consommation
                            </a>
                        </li>
                    @endif
                </ul>

                <!-- Menu utilisateur à droite -->
                <ul class="navbar-nav ms-auto">
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin*') ? 'active' : '' }}" 
                                   href="{{ url('/admin') }}">
                                     Administration
                                </a>
                            </li>
                        @endif
                        
                    
                        
                        <!-- Bouton déconnexion -->
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-outline-danger nav-link border-0"
                                        onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?')">
                                     Déconnexion
                                </button>
                            </form>
                        </li>
                    @endauth

                    @guest
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" 
                               href="{{ route('login') }}">
                                Connexion
                            </a>
                        </li>
                        
                        @if(Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" 
                                   href="{{ route('register') }}">
                                    Inscription
                                </a>
                            </li>
                        @endif
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="container py-4">
        {{-- Messages flash globaux --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong> Succès !</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erreur !</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong> Attention !</strong> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong> Information :</strong> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Contenu principal --}}
        @yield('content')
    </main>

<!--  
    <footer class="bg-light mt-5 py-4 border-top">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0 text-muted">
                        &copy; {{ date('Y') }} WattsUp - Gestion énergétique intelligente-Triki Mabrouka
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <small class="text-muted">
                        Version 1.0 | 🌱 Pour un avenir plus vert
                    </small>
                </div>
            </div>
        </div>
    </footer> -->

    {{-- Bootstrap Bundle (JS + Popper) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>

    {{-- Script pour animation navbar au scroll --}}
    <script>
        // Animation navbar au scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Auto-hide des alerts après 5 secondes
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>

    {{-- Pour injecter des scripts spécifiques depuis une vue enfant --}}
    @stack('scripts')
</body>
</html>