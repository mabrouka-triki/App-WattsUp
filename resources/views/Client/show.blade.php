@extends('App.layoutClient')

@section('head')
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="container">
    <h1 class="mb-4">Habitation : {{ $habitation->adresse_habitation }}</h1>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Informations générales</h5>
            <ul class="list-unstyled mb-0">
                <li><strong>Type :</strong> {{ $habitation->type_habitation }}</li>
                <li><strong>Surface :</strong> {{ $habitation->surfaces }} m²</li>
                <li><strong>Nombre d’occupants :</strong> {{ $habitation->nb_occupants }}</li>
            </ul>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h2 class="mb-3">Compteurs associés</h2>

    <table class="table align-middle">
        <thead>
            <tr>
                <th>Type</th>
                <th>Référence</th>
                <th>Date d’ajout</th>
            </tr>
        </thead>
        <tbody>
            @forelse($habitation->compteurs as $compteur)
                <tr>
                    <td>{{ $compteur->Type_compteur }}</td>
                    <td>{{ $compteur->Reference_compteur }}</td>
                    <td>{{ $compteur->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="3">Aucun compteur n’est encore enregistré.</td></tr>
            @endforelse
        </tbody>
    </table>

    <a class="btn btn-primary"
       href="{{ route('client.compteur.create', $habitation->id_habitation) }}">
        + Ajouter un compteur
    </a>
</div>
@endsection
