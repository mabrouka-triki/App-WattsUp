@extends('App.layoutClient')

@section('head')
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
@endsection

@section('title', 'Mes habitations')

@section('content')

    
    <!-- Ajout de la section pour les messages -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

<div class="habitations-container">
    <h1 class="habitation-title">Bienvenue ! {{ Auth::user()->name }}</h1>
    
    <h2>Mes habitations</h2>
    
    @if($habitations->isEmpty())
        <p>Aucune habitation enregistrée.</p>
    @else
        <div class="habitation-list">
            @foreach ($habitations as $habitation)
                <div class="habitation-item">
                    <div class="habitation-details">
                    {{ $habitation->adresse_habitation }} ({{ $habitation->type_habitation }} - {{ $habitation->surfaces }} m², {{ $habitation->nb_occupants }} occupants)
                    </div>
                    <form method="POST" action="{{ route('client.habitation.delete', $habitation->id_habitation) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">Supprimer</button>
                    </form>
                </div>
                <a class href="{{ route('client.compteur.create', $habitation->id_habitation) }}"
   class="btn-compteur">
    + Compteur
</a>

            @endforeach
        </div>
    @endif
    
    <div class="add-habitation-section">
        <h3 class="section-title">Ajouter une habitation</h3>
        
        <form action="{{ route('Client.store') }}" method="POST" class="habitation-form">
            @csrf
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Adresse</label>
                    <input type="text" name="adresse_habitation" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Type</label>
                    <select name="type_habitation" class="form-control" required>
                        <option value="Maison">Maison</option>
                        <option value="Appartement">Appartement</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Surface (m²)</label>
                    <input type="number" name="surfaces" class="form-control" required min="1">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Nombre d'occupants</label>
                    <input type="number" name="nb_occupants" class="form-control" required min="1">
                </div>
            </div>
            
            <div class="btn-add-container">
                <button type="submit" class="btn-add">Ajouter</button>
            </div>
            
            <div>

        </form>
    </div>
</div>
@endsection