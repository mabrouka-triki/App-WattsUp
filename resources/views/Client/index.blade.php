@extends('App.layoutClient')

@section('head')
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
@endsection

@section('title', 'Bienvenue sur WattsUp')

@section('content')
<div class="full-screen-center">
    <h1 class="fw-bold">Bienvenue ! {{ Auth::user()->name }}</h1>
    <p>Suivez et analysez votre consommation d'énergie</p>

    {{-- Message de succès --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

   <h2 class="mt-5">Mes habitations</h2>

@if($habitations->isEmpty())
    <p>Vous n'avez encore ajouté aucune habitation.</p>
@else
    <div class="table-container">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Adresse</th>
                    <th>Type</th>
                    <th>Surface (m²)</th>
                    <th>Occupants</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($habitations as $habitation)
                    <tr>
                        <td>{{ $habitation->adresse_habitation }}</td>
                        <td>{{ $habitation->type_habitation }}</td>
                        <td>{{ $habitation->surfaces }}</td>
                        <td>{{ $habitation->nb_occupants }}</td>
                        <td>
                        
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Supprimer cette habitation ?')" class="action-btn btn-delete">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
