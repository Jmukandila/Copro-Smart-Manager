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
    Schema::create('reports', function (Blueprint $table) {
        $table->id();
        // Relie le signalement à l'utilisateur (le locataire)
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        $table->string('title'); // Ex: "Fuite d'eau"
        $table->text('description'); // Détails du problème
        $table->string('apartment_number')->nullable(); // Rappel de l'appart concerné
        
        // Système de suivi par statut
        $table->string('status')->default('en_attente'); 
        // Valeurs possibles : en_attente, en_cours, resolu, rejete
        
        $table->text('admin_comment')->nullable(); // Réponse du syndic
        $table->string('priority')->default('normale'); // basse, normale, urgente
        
        $table->timestamps(); // created_at (date du signalement) et updated_at
    });
}
};
