@extends('App.layoutClient')

@section('head')
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
@endsection

@section('title', 'Bienvenue sur WattsUp')

@section('content')
<div class="full-screen-center">
    <h1 class="fw-bold">Bienvenue ! {{ Auth::user()->name }}</h1>
    <p>Suivez et analyser votre consommation d'énergie</p>

    <div class="icons-container d-flex justify-content-center gap-5 my-4">
        <img src="{{ asset('assets/evaluation.svg') }}" alt="Graphique" width="80">
        <img src="{{ asset('assets/pdf.svg') }}" alt="PDF" width="80">
        <img src="{{ asset('assets/croissance.svg') }}" alt="Rapport" width="80">
    </div>
</div>
@endsection