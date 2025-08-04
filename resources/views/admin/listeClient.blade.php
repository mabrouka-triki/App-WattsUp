@extends('App.layoutAdmin')

@section('title', 'Liste des Clients')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Liste des Clients</h2>

    @if($clients->isEmpty())
        <div class="alert alert-info">Aucun client trouvé.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Date de création</th>
                    <th>Nombre d'habitations</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                    <tr>
                        <td>{{ $client->name }}</td>
                        <td>{{ $client->email }}</td>
                        <td>{{ $client->date_creation_client ? $client->date_creation_client->format('d/m/Y') : '-' }}</td>
                        <td>{{ $client->habitations->count() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
