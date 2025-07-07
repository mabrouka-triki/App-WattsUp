@extends('App.layout')

@section('content')
<div class="container mt-5" style="max-width: 600px;">
    <h2 class="mb-4">Inscription</h2>
@if ($errors->any())
    <div class="alert alert-danger">
   
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
   
    </div>
@endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Adresse Email</label>
            <input type="email" class="form-control" name="email" required value="{{ old('email') }}">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <div class="mb-3">
            <label for="adresse_habitation" class="form-label">Adresse de l'habitation</label>
            <input type="text" class="form-control" name="adresse_habitation" required value="{{ old('adresse_habitation') }}">
        </div>

        <div class="mb-3">
            <label for="type_habitation" class="form-label">Type d'habitation</label>
            <select name="type_habitation" class="form-select" required>
                <option value="">-- Choisir --</option>
                <option value="Appartement">Appartement</option>
                <option value="Maison">Maison</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="surfaces" class="form-label">Surface (en m²)</label>
            <input type="number" class="form-control" name="surfaces" required min="1">
        </div>

        <div class="mb-3">
            <label for="nb_occupants" class="form-label">Nombre d'occupants</label>
            <input type="number" class="form-control" name="nb_occupants" required min="1">
        </div>

        <button type="submit" class="btn btn-primary w-100">S'inscrire</button>

        <div class="mt-3 text-center">
            <a href="{{ route('login') }}">Déjà un compte ? Se connecter</a>
        </div>
    </form>
</div>
@endsection