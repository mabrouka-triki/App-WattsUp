<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      
         Schema::create('user_roles', function (Blueprint $table) {
  
    $table->unsignedBigInteger('id_user');
    $table->unsignedBigInteger('id_role');

    $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('id_role')->references('id_role')->on('roles')->onDelete('cascade');

    $table->unique(['id_user', 'id_role']); // Pour éviter les doublons
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
