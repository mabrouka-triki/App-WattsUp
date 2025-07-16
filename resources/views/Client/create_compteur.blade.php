@extends('App.layoutClient')

@section('head')
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
@endsection

@section('title', 'Ajouter un compteur')
    
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
@section('content')
<div class="compteur-page">
    <div class="compteur-header">
        <h2>Ajouter un compteur pour l'habitation<br>{{ $habitation->adresse_habitation }}</h2>
    </div>

    <form action="{{ route('client.compteur.store', $habitation->id_habitation) }}" method="POST" class="compteur-form">
        @csrf

        <!-- Type de compteur -->
        <div class="form-group">
            <label for="Type_compteur" class="form-label">Type de compteur</label>
            <select name="Type_compteur" id="Type_compteur" class="form-select @error('Type_compteur') is-invalid @enderror" required>
                <option value="">-- Sélectionnez --</option>
                @foreach($typesCompteurs as $key => $type)
                    <option value="{{ $key }}" data-description="{{ $type->description }}" 
                        {{ old('Type_compteur') === $key ? 'selected' : '' }}>
                        {{ $type->label }}
                    </option>
                @endforeach
            </select>
            @error('Type_compteur')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            
            <!-- Description dynamique -->
            <p id="compteur-description" class="compteur-description">
                Veuillez sélectionner un type de compteur pour afficher sa description.
            </p>
        </div>

        <!-- Référence du compteur -->
        <div class="form-group">
            <label for="Reference_compteur" class="form-label">Référence du compteur</label>
            <input type="text" name="Reference_compteur" id="Reference_compteur" 
                   class="form-control @error('Reference_compteur') is-invalid @enderror"
                   value="{{ old('Reference_compteur') }}" required minlength="3">
            @error('Reference_compteur')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Champs facultatifs -->
        <div class="form-row" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
            <div class="form-group optional-field">
                <label for="date_installation" class="form-label">Date d'installation</label>
                <input type="date" name="date_installation" id="date_installation" 
                       class="form-control" value="{{ old('date_installation') }}">
            </div>
        </div>

        <button type="submit" class="btn-submit">
            Ajouter le compteur
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('Type_compteur');
    const description = document.getElementById('compteur-description');

    function updateDescription() {
        const selectedOption = select.selectedOptions[0];
        description.textContent = selectedOption.dataset.description || 
            "Veuillez sélectionner un type de compteur pour afficher sa description.";
    }

    // Initialise au chargement
    updateDescription();
    
    // Écoute les changements
    select.addEventListener('change', updateDescription);
});
</script>
@endpush