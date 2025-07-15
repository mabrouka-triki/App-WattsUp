@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Ajouter un compteur ({{ $habitation->adresse_habitation }})</h1>

    <form method="POST"
          action="{{ route('client.compteur.store', $habitation->id_habitation) }}">
        @csrf

        <div class="mb-3">
            <label for="Type_compteur" class="form-label">Type de compteur</label>
            <select id="Type_compteur" name="Type_compteur"
                    class="form-select @error('Type_compteur') is-invalid @enderror">
                <option value="">-- Sélectionner --</option>
                <option value="Électricité" {{ old('Type_compteur')=='Électricité' ? 'selected' : '' }}>Électricité</option>
                <option value="Gaz"         {{ old('Type_compteur')=='Gaz'         ? 'selected' : '' }}>Gaz</option>
                <option value="Eau"         {{ old('Type_compteur')=='Eau'         ? 'selected' : '' }}>Eau</option>
            </select>
            @error('Type_compteur') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="Reference_compteur" class="form-label">Référence</label>
            <input type="text" id="Reference_compteur" name="Reference_compteur"
                   class="form-control @error('Reference_compteur') is-invalid @enderror"
                   value="{{ old('Reference_compteur') }}">
            @error('Reference_compteur') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button class="btn btn-success">Enregistrer</button>
        <a href="{{ route('client.habitation.show', $habitation->id_habitation) }}"
           class="btn btn-link">Annuler</a>
    </form>
</div>
@endsection
