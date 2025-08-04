@extends('App.layoutClient')

{{-- Inclusion du fichier CSS personnalisé --}}
@section('head')
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
@endsection

{{-- Titre de la page --}}
@section('title', 'Ajouter un compteur')

{{-- Affichage des messages flash pour informer l'utilisateur après soumission du formulaire --}}
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

@section('content')
<div class="compteur-page">
    <div class="compteur-header">
        {{-- Affichage dynamique de l'adresse de l'habitation pour contextualiser l'ajout --}}
        <h2>Ajouter un compteur pour l'habitation<br>{{ $habitation->adresse_habitation }}</h2>
    </div>

    {{-- Formulaire d'ajout d'un nouveau compteur lié à une habitation précise --}}
    <form action="{{ route('client.compteur.store', $habitation->id_habitation) }}" method="POST" class="compteur-form">
        @csrf {{-- Protection CSRF intégrée de Laravel --}}

        {{-- Sélection du type de compteur (électrique, gaz, etc.) --}}
        <div class="form-group">
            <label for="Type_compteur" class="form-label">Type de compteur</label>
            <select name="Type_compteur" id="Type_compteur" class="form-select @error('Type_compteur') is-invalid @enderror" required>
                <option value="">-- Sélectionnez --</option>
                @foreach($typesCompteurs as $key => $type)
                    {{-- Chaque option contient une description en data-* pour affichage dynamique --}}
                    <option value="{{ $key }}" data-description="{{ $type->description }}" 
                        {{ old('Type_compteur') === $key ? 'selected' : '' }}>
                        {{ $type->label }}
                    </option>
                @endforeach
            </select>
            @error('Type_compteur')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            
            {{-- Paragraphe affichant dynamiquement la description du type sélectionné --}}
            <p id="compteur-description" class="compteur-description">
                Veuillez sélectionner un type de compteur pour afficher sa description.
            </p>
        </div>

        {{-- Champ pour la référence du compteur (souvent un identifiant unique fourni par le fournisseur) --}}
        <div class="form-group">
            <label for="Reference_compteur" class="form-label">Référence du compteur</label>
            <input type="text" name="Reference_compteur" id="Reference_compteur" 
                   class="form-control @error('Reference_compteur') is-invalid @enderror"
                   value="{{ old('Reference_compteur') }}" required minlength="3">
            @error('Reference_compteur')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Champ facultatif pour préciser la date d'installation du compteur --}}
        <div class="form-row" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
            <div class="form-group optional-field">
                <label for="date_installation" class="form-label">Date d'installation</label>
                <input type="date" name="date_installation" id="date_installation" 
                       class="form-control" value="{{ old('date_installation') }}">
            </div>
        </div>

        {{-- Bouton de soumission du formulaire --}}
        <button type="submit" class="btn-submit">
            Ajouter le compteur
        </button>
        
    </form>
</div>
@endsection

{{-- Script JavaScript pour afficher dynamiquement la description du type sélectionné --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('Type_compteur');
    const description = document.getElementById('compteur-description');

    function updateDescription() {
        const selectedOption = select.selectedOptions[0];
        // Affiche la description liée à l’option sélectionnée ou un texte par défaut
        description.textContent = selectedOption.dataset.description || 
            "Veuillez sélectionner un type de compteur pour afficher sa description.";
    }

    // Mise à jour dès le chargement de la page
    updateDescription();
    
    // Mise à jour à chaque changement de sélection
    select.addEventListener('change', updateDescription);
});
</script>
@endpush
