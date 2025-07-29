@extends('App.layoutClient')

@section('head')
    {{-- Inclusion du fichier CSS de style --}}
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
@endsection

@section('title', 'Suivi de consommation')

@section('content')
<div class="container">
    {{-- Affichage des messages de retour (succès ou erreur) après une action utilisateur --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Titre principal indiquant le type de compteur suivi --}}
    <h2>Suivi de consommation - {{ $compteur->Type_compteur }}</h2>

    {{-- Si des statistiques sont disponibles, on les affiche --}}
    @if($stats)
    <div class="alert alert-info mb-4">
        <h4>Analyse de votre consommation</h4>
        <ul>
            <li>Tendance: 
                {{-- Affichage de la tendance analysée sous forme textuelle --}}
                <strong>{{ $stats['tendance'] == 'hausse' ? 'À la hausse' : ($stats['tendance'] == 'baisse' ? 'À la baisse' : 'Stable') }}</strong>
            </li>
            <li>Variation moyenne: <strong>{{ $stats['moyenne'] }} kWh</strong></li>
            <li>Nombre d'anomalies détectées: <strong>{{ $stats['anomalies'] }}</strong></li>
        </ul>

        {{-- Affichage de conseils uniquement si la tendance est à la hausse --}}
        @if($stats['tendance'] == 'hausse')
        <div class="mt-3">
            <h5>Conseils pour réduire votre consommation:</h5>
            <ul>
                <li>Vérifiez les appareils en veille</li>
                <li>Optimisez l'utilisation de votre chauffage/climatisation</li>
                <li>Pensez à isoler votre habitation</li>
            </ul>
        </div>
        @endif
    </div>
    @endif

    <div class="row">
        {{-- Graphique de consommation (utilise Chart.js) --}}
        <div class="col-md-8">
            <canvas id="consommationChart" height="200"></canvas>
        </div>

        {{-- Formulaire pour ajouter un nouveau relevé de consommation --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Ajouter un relevé</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('consommation.store', $compteur) }}" enctype="multipart/form-data">
                        {{-- Protection CSRF obligatoire dans les formulaires POST Laravel --}}
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Date de relevé</label>
                            {{-- La date ne peut pas dépasser la date du jour --}}
                            <input type="date" name="date_relev_consommation" class="form-control" required max="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Valeur (kWh)</label>
                            {{-- Le champ accepte des décimales, avec une validation côté HTML --}}
                            <input type="number" step="0.01" name="valeur_conso" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Historique de tous les relevés enregistrés --}}
    <div class="mt-4">
        <h3>Historique des relevés</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Valeur (kWh)</th>
                    <th>Variation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                {{-- Affichage dynamique de chaque relevé de consommation --}}
                @forelse($consommations as $index => $conso)
                <tr class="{{ $index > 0 && abs($consommations[$index-1]->valeur_conso - $conso->valeur_conso) > 2 * abs($stats['moyenne'] ?? 0) ? 'table-warning' : '' }}">
                    <td>{{ date('d/m/Y', strtotime($conso->date_relev_consommation)) }}</td>
                    <td>{{ number_format($conso->valeur_conso, 2) }}</td>
                    <td>
                        @if($index > 0)
                            @php
                                // Calcul de la variation avec le relevé précédent
                                $variation = $consommations[$index-1]->valeur_conso - $conso->valeur_conso;

                                // Détection d'une anomalie si la variation est supérieure à 2 fois la moyenne absolue
                                $isAnomaly = abs($variation) > 2 * abs($stats['moyenne'] ?? 0);
                            @endphp
                            <span class="{{ $isAnomaly ? 'text-danger fw-bold' : '' }}">
                                {{ number_format($variation, 2) }} kWh
                                {{-- Icône d'avertissement si anomalie détectée --}}
                                @if($isAnomaly) <i class="fas fa-exclamation-triangle"></i> @endif
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td>
    <button class="btn btn-sm btn-warning edit-btn" 
            data-id="{{ $conso->id }}" 
            data-value="{{ $conso->valeur_conso }}">
        <i class="fas fa-edit"></i> Modifier
    </button>
</td>
</td>
                </tr>
                @empty
                {{-- Message si aucun relevé n'est encore enregistré --}}
                <tr>
                    <td colspan="4" class="text-center">Aucun relevé enregistré</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<!-- Modal de modification -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editForm" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Modifier la consommation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nouvelle valeur (kWh)</label>
                    <input type="number" step="0.01" name="valeur_conso" class="form-control" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
{{-- Inclusion de la librairie Chart.js pour l'affichage du graphique --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('consommationChart').getContext('2d');

    // Conversion des dates PHP vers un format lisible pour le graphique (d/m/Y)
    const dates = @json($consommations->pluck('date_relev_consommation')->map(function($d) {
        return date('d/m/Y', strtotime($d));
    }));

    // Extraction des valeurs de consommation pour les courbes
    const valeurs = @json($consommations->pluck('valeur_conso'));

    // Création du graphique avec Chart.js
    new Chart(ctx, {
        type: 'line', // Type de graphique: ligne
        data: {
            labels: dates.reverse(), // Inversion pour affichage chronologique
            datasets: [{
                label: 'Consommation (kWh)',
                data: valeurs.reverse(),
                borderColor: '#2e7d32',
                tension: 0.1, // Adoucit la courbe
                fill: false // Pas de remplissage sous la ligne
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        // Affiche une variation dans le tooltip si index > 0
                        afterLabel: function(context) {
                            const index = context.dataIndex;
                            if (index > 0) {
                                const variation = valeurs[index-1] - valeurs[index];
                                return `Variation: ${variation.toFixed(2)} kWh`;
                            }
                            return '';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: false // L'échelle Y commence à la plus petite valeur utile
                }
            }
        }
    });
});
// Gestion de la modification
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const consoId = this.getAttribute('data-id');
        const consoValue = this.getAttribute('data-value');
        
        const form = document.getElementById('editForm');
        form.action = `/consommation/${consoId}`;
        form.querySelector('input[name="valeur_conso"]').value = consoValue;
        
        const modal = new bootstrap.Modal(document.getElementById('editModal'));
        modal.show();
    });
});

// Soumission du formulaire
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    fetch(this.action, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            valeur_conso: this.valeur_conso.value
        })
    })
    .then(response => {
        if (response.ok) {
            window.location.reload();
        } else {
            alert('Erreur lors de la modification');
        }
    });
});
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.edit-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const row = btn.closest('tr');
            row.querySelector('.conso-display').classList.add('d-none');
            row.querySelector('.conso-edit').classList.remove('d-none');
            btn.classList.add('d-none');
            row.querySelector('.save-btn').classList.remove('d-none');
        });
    });

    document.querySelectorAll('.save-btn').forEach(function (btn) {
        btn.addEventListener('click', async function () {
            const row = btn.closest('tr');
            const input = row.querySelector('.conso-edit');
            const valeur = input.value;
            const consoId = btn.dataset.id;
            const compteurId = btn.dataset.compteur;

            const url = `/consommation/${compteurId}/${consoId}`;
            console.log("URL:", url);

            try {
                const response = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ valeur_conso: valeur })
                });

                if (!response.ok) throw new Error("Erreur de mise à jour");

                row.querySelector('.conso-display').textContent = parseFloat(valeur).toFixed(2);
                row.querySelector('.conso-display').classList.remove('d-none');
                input.classList.add('d-none');
                btn.classList.add('d-none');
                row.querySelector('.edit-btn').classList.remove('d-none');
            } catch (err) {
                alert("Erreur : " + err.message);
            }
        });
    });
});

</script>
@endpush
@endsection
