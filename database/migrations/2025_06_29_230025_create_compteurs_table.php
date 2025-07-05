<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('compteurs', function (Blueprint $table) {
    $table->id('id_compteur');
    $table->string('Type_compteur', 100);
    $table->string('Reference_compteur', 255);
    $table->unsignedBigInteger('id_habitation');

    $table->timestamps();

    $table->foreign('id_habitation')
          ->references('id_habitation')
          ->on('habitations')
          ->onDelete('cascade');
});

    }

    public function down(): void
    {
        Schema::dropIfExists('compteurs');
    }
};
