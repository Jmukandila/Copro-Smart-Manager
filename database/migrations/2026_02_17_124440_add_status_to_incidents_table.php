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
        $table->string('status')->default('en_attente');
        $table->text('admin_comment')->nullable();
        // On a enlevé internal_notes car elle existe déjà !
    });
}

    /**
     * Reverse the migrations.
     */
 public function down(): void
{
    Schema::table('incidents', function (Blueprint $table) {
        $table->dropColumn(['status', 'admin_comment', 'internal_notes']);
    });
}
};
