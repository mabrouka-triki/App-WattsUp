<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id('id_facture'); 
            $table->string('Fournisseur', 100);
            $table->date('Date_de_facture');
            $table->decimal('Montant', 15, 2); 
            $table->decimal('Consommation', 10, 2);
            $table->unsignedBigInteger('id_compteur'); // clé étrangère int

            $table->timestamps();

            $table->foreign('id_compteur')->references('id_compteur')->on('compteurs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
