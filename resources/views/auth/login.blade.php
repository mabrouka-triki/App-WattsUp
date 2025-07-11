@extends('App.layout')
@section('head')
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
@section('title', 'Connexion')



@section('content')
<div class="login-container">
    <div class="text-center mb-4">
        <img src="{{ asset('assets/eco.svg') }}" alt="ecologique">
 </div>
    <h2 class="mb-4 text-center">Connexion</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('doLogin') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Adresse email</label>
            <input type="email" name="email" id="email" class="form-control" required autofocus>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

   

        <button type="submit" class="btnlogin btn-primary w-100">Se connecter</button>
        <div class="mt-3 text-center">
            <a href="{{ route('register') }}">Pas encore inscrit ? Créez votre compte</a>
        </div>


    </form>
</div>


    
@endsection
