<!-- @extends('App.layout')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container mt-5">
    <h1 class="text-center">Tableau de bord Administrateur</h1>
    <p class="text-center">Bienvenue {{ Auth::user()->name }}, vous êtes connecté en tant qu'administrateur.</p>

    <div class="text-center mt-4">
        <a href="{{ route('home') }}" class="btn btn-primary">Retour à l'accueil</a>
    </div>
</div>
@endsection -->