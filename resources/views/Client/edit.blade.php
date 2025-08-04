@extends('App.layoutClient')

@section('head')
    {{-- Inclusion du fichier CSS de style --}}
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
@endsection

@section('title', 'modification ')

@section('content')

    <h2>Modifier l’habitation</h2>

    <form action="{{ route('client.habitation.update', $habitation->id_habitation) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Adresse</label>
            <input type="text" name="adresse_habitation" value="{{ old('adresse_habitation', $habitation->adresse_habitation) }}" required>
        </div>

        <div class="form-group">
            <label>Type</label>
            <select name="type_habitation" required>
                <option value="Maison" {{ $habitation->type_habitation === 'Maison' ? 'selected' : '' }}>Maison</option>
                <option value="Appartement" {{ $habitation->type_habitation === 'Appartement' ? 'selected' : '' }}>Appartement</option>
            </select>
        </div>

        <div class="form-group">
            <label>Surface (m²)</label>
            <input type="number" name="surfaces" value="{{ old('surfaces', $habitation->surfaces) }}" required min="1">
        </div>

        <div class="form-group">
            <label>Nombre d’occupants</label>
            <input type="number" name="nb_occupants" value="{{ old('nb_occupants', $habitation->nb_occupants) }}" required min="1">
        </div>

        <button type="submit">Mettre à jour</button>
    </form>
</div>
@endsection
