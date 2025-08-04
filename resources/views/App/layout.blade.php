<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'WattsUp')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
 
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
          <div class="container">
            <!-- Logo et Brand -->
   <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('assets/WattsUp.svg') }}"
                     alt="WattsUp Logo"
                     style="height: 50px"> WattsUp
            </a>

            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/home') }}">Accueil</a></li>
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Connexion</a></li>
                    @endguest
                    @auth
                        <li class="nav-item">
                            <span class="nav-link">Bonjour, {{ Auth::user()->name }}</span>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="btn btn-link nav-link" type="submit">Déconnexion</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- CONTENU -->
    <main class="container py-4">
        @yield('content')
    </main>
</body>
</html>
