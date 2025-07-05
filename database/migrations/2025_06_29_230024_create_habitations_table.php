<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



return new class extends Migration {
    public function up(): void
    {
        Schema::create('habitations', function (Blueprint $table) {
            $table->id('id_habitation');
            $table->string('adresse_habitation', 255);
            $table->string('type_habitation', 255);
            $table->decimal('surfaces', 8, 2);
            $table->integer('nb_occupants');

            // Clé étrangère vers users.id
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('habitations');
    }
};

