@extends('App.layoutClient') 

@section('head')

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
@endsection

@section('title', 'Mes habitations') {{-- Définition du titre de la page pour l’onglet du navigateur --}}

@section('content')

    {{-- Affichage conditionnel des messages flash (succès ou erreur) après une action utilisateur.
         Ces messages proviennent généralement du contrôleur, pour améliorer l'expérience utilisateur. --}}
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
    
    {{-- Message de bienvenue dynamique, affichant le nom de l’utilisateur actuellement connecté (via Auth) --}}
    <h1 class="habitation-title">Bienvenue ! {{ Auth::user()->name }}</h1>
    
    <h2>Mes habitations</h2>
    
    {{-- Vérifie si l’utilisateur n’a encore enregistré aucune habitation --}}
    @if($habitations->isEmpty())
        <p>Aucune habitation enregistrée.</p>
    @else
        {{-- Liste les habitations existantes de l’utilisateur --}}
        <div class="habitation-list">
            @foreach ($habitations as $habitation)
                <div class="habitation-item">
                    <div class="habitation-details">
                        {{-- Affiche les principales caractéristiques de l’habitation --}}
                        {{ $habitation->adresse_habitation }} 
                        ({{ $habitation->type_habitation }} - {{ $habitation->surfaces }} m², {{ $habitation->nb_occupants }} occupants)
                    </div>

                    {{-- Formulaire permettant de supprimer une habitation. 
                         Utilise la méthode DELETE (via spoofing HTTP avec @method) --}}
                    <form method="POST" action="{{ route('client.habitation.delete', $habitation->id_habitation) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">Supprimer</button>
                    </form>
                </div>

                {{-- Lien permettant d'ajouter un compteur à cette habitation (relation 1-n) --}}
                <a href="{{ route('client.compteur.create', $habitation->id_habitation) }}"
                   class="btn-compteur">
                   + Compteur
                </a>
            @endforeach
        </div>
    @endif

    {{-- Formulaire pour ajouter une nouvelle habitation à la base de données --}}
    <div class="add-habitation-section">
        <h3 class="section-title">Ajouter une habitation</h3>
        
        <form action="{{ route('Client.store') }}" method="POST" class="habitation-form">
            @csrf {{-- Jeton CSRF pour la sécurité contre les requêtes forgées --}}

            {{--  Adresse et type d’habitation --}}
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
            
            {{-- Ligne 2 : Surface et nombre d’occupants --}}
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
            
            {{-- Bouton pour valider et soumettre le formulaire d’ajout --}}
            <div class="btn-add-container">
                <button type="submit" class="btn-add">Ajouter</button>
            </div>

        </form>
    </div>
</div>
@endsection
