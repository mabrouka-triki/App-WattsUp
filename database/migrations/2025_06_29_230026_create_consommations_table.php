<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consommations', function (Blueprint $table) {
            $table->id('id_consommation'); // auto-increment int primary key
            $table->date('date_relev_consommation');
            $table->integer('valeur_conso');
            $table->unsignedBigInteger('id_compteur'); // clé étrangère int

            $table->timestamps();

            $table->foreign('id_compteur')
                ->references('id_compteur')
                ->on('compteurs')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consommations');
    }
};
