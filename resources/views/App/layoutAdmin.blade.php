<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin - WattsUp')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
</head>
<body>
    <!-- NAVBAR ADMIN -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.index') }}">Admin WattsUp</a>
            <ul class="navbar-nav ms-auto">
                @auth('admin')
                    <li class="nav-item">
                        <span class="nav-link text-white">Bonjour, {{ Auth::guard('admin')->user()->nom_admin }}</span>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button class="btn btn-link nav-link text-white" type="submit">Déconnexion</button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>

    <main class="container py-4">
        @yield('content')
    </main>
</body>
</html>
