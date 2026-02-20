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
        Schema::table('incidents', function (Blueprint $table) {
            // On lie l'incident à un prestataire (issu de la table users)
            // Si le prestataire est supprimé, le champ devient simplement vide (null)
            $table->foreignId('provider_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Champ pour que Josh puisse noter des infos privées
            $table->text('internal_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            // On retire d'abord la clé étrangère, puis les colonnes
            $table->dropForeign(['provider_id']);
            $table->dropColumn(['provider_id', 'internal_notes']);
        });
    }
};