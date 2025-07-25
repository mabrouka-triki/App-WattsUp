<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Habitation;
use App\Models\Compteur;
use App\Models\Consommation;
use App\Models\Facture;
use Illuminate\Support\Facades\Hash;

class WattsUpSeeder extends Seeder
{
    public function run(): void
    {
        // Créer les rôles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $clientRole = Role::firstOrCreate(['name' => 'client']);

        // Créer l'admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('adminpassword'),
                'date_creation_client' => null,
            ]
        );
        $admin->roles()->syncWithoutDetaching([$adminRole->id_role]);

        // Créer Mabrouka
        $mabrouka = User::firstOrCreate(
            ['email' => 'mabrouka@gmail.com'],
            [
                'name' => 'Mabrouka tkt',
                'password' => bcrypt('password123'),
                'date_creation_client' => now()->subYear(),
            ]
        );
        $mabrouka->roles()->syncWithoutDetaching([$clientRole->id_role]);

        foreach ([
            ['adresse' => '10 Rue Lafayette, Paris', 'type' => 'Appartement', 'surface' => 80, 'occupants' => 2],
            ['adresse' => '120 Avenue République, Lille', 'type' => 'Maison', 'surface' => 120, 'occupants' => 4],
        ] as $habData) {
            $hab = Habitation::create([
                'adresse_habitation' => $habData['adresse'],
                'type_habitation' => $habData['type'],
                'surfaces' => $habData['surface'],
                'nb_occupants' => $habData['occupants'],
                'user_id' => $mabrouka->id,
            ]);

            $compteur = Compteur::create([
                'Type_compteur' => 'Electricité',
                'Reference_compteur' => strtoupper(uniqid('CMP')),
                'id_habitation' => $hab->id_habitation,
            ]);

            for ($i = 1; $i <= 3; $i++) {
                Consommation::create([
                    'date_relev_consommation' => now()->subDays($i * 10),
                    'valeur_conso' => rand(100, 180),
                    'id_compteur' => $compteur->id_compteur,
                ]);
            }

            Facture::create([
                'Fournisseur' => 'EDF',
                'Date_de_facture' => now()->subDays(25),
                'Montant' => rand(80, 130),
                'Consommation' => rand(90, 160),
                'id_compteur' => $compteur->id_compteur,
            ]);
        }

        // Autres clients avec plus de données
        $faker = \Faker\Factory::create('fr_FR');
        for ($u = 1; $u <= 10; $u++) {
            $user = User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => bcrypt('password123'),
                'date_creation_client' => now()->subDays(rand(30, 300)),
            ]);
            $user->roles()->syncWithoutDetaching([$clientRole->id_role]);

            // Chaque client a 1 à 3 habitations
            for ($i = 1; $i <= rand(1, 3); $i++) {
                $hab = Habitation::create([
                    'adresse_habitation' => $faker->streetAddress() . ', ' . $faker->city(),
                    'type_habitation' => $faker->randomElement(['Maison', 'Appartement']),
                    'surfaces' => rand(60, 150),
                    'nb_occupants' => rand(1, 6),
                    'user_id' => $user->id,
                ]);

                $compteur = Compteur::create([
                    'Type_compteur' => $faker->randomElement(['Electricité', 'Gaz']),
                    'Reference_compteur' => strtoupper(uniqid('CMP')),
                    'id_habitation' => $hab->id_habitation,
                ]);

                for ($j = 1; $j <= rand(2, 4); $j++) {
                    Consommation::create([
                        'date_relev_consommation' => now()->subDays($j * 15),
                        'valeur_conso' => rand(70, 200),
                        'id_compteur' => $compteur->id_compteur,
                    ]);
                }

                Facture::create([
                    'Fournisseur' => $faker->randomElement(['EDF', 'Engie', 'TotalEnergies']),
                    'Date_de_facture' => now()->subDays(rand(10, 60)),
                    'Montant' => rand(60, 200),
                    'Consommation' => rand(90, 170),
                    'id_compteur' => $compteur->id_compteur,
                ]);
            }
        }
    }
}
