<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Habitation;
use App\Models\Compteur;
use App\Models\Consommation;
use App\Models\Facture;
use Illuminate\Database\Seeder;

class WattsUpSeeder extends Seeder
{
    public function run(): void
    {
        // Création d'un admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('adminpassword'),
            'role' => 'admin',
            // pas forcément besoin des champs client pour l'admin
            'date_creation_client' => null,
            'type_client' => null,
        ]);

        // Création des clients
        $user1 = User::create([
            'name' => 'Mabrouka tkt',
            'email' => 'mabrouka@gmail.com',
            'password' => bcrypt('password123'),
            'date_creation_client' => now()->subYear(),
            'type_client' => 'perso',
            'role' => 'client',
        ]);

        $user2 = User::create([
            'name' => 'Bob Martin',
            'email' => 'bob@example.com',
            'password' => bcrypt('password123'),
            'date_creation_client' => now()->subMonths(6),
            'type_client' => 'pros',
            'role' => 'client',
        ]);

        // Habitations liées aux clients (pas à l'admin)
        $hab1 = Habitation::create([
            'adresse_habitation' => '123 Rue de Paris, Lyon',
            'type_habitation' => 'Appartement',
            'surfaces' => 85,
            'nb_occupants' => 3,
            'user_id' => $user1->id,
        ]);

        $hab2 = Habitation::create([
            'adresse_habitation' => '45 Avenue Victor Hugo, Marseille',
            'type_habitation' => 'Maison',
            'surfaces' => 150,
            'nb_occupants' => 5,
            'user_id' => $user2->id,
        ]);

        // Compteurs
        $compteur1 = Compteur::create([
            'Type_compteur' => 'Electricité',
            'Reference_compteur' => 'ELEC12345',
            'id_habitation' => $hab1->id_habitation,
        ]);

        $compteur2 = Compteur::create([
            'Type_compteur' => 'Gaz',
            'Reference_compteur' => 'GAZ67890',
            'id_habitation' => $hab2->id_habitation,
        ]);

        // Consommations
        Consommation::create([
            'date_relev_consommation' => now()->subDays(7),
            'valeur_conso' => 150,
            'id_compteur' => $compteur1->id_compteur,
        ]);

        Consommation::create([
            'date_relev_consommation' => now()->subDays(3),
            'valeur_conso' => 75,
            'id_compteur' => $compteur2->id_compteur,
        ]);

        // Factures
        Facture::create([
            'Fournisseur' => 'EDF',
            'Date_de_facture' => now()->subMonth(),
            'Montant' => 120.50,
            'Consommation' => 150,
            'id_compteur' => $compteur1->id_compteur,
        ]);

        Facture::create([
            'Fournisseur' => 'GRDF',
            'Date_de_facture' => now()->subMonth(),
            'Montant' => 80.75,
            'Consommation' => 75,
            'id_compteur' => $compteur2->id_compteur,
        ]);
    }
}
