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
    Schema::create('incidents', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // <-- CETTE LIGNE EST ESSENTIELLE
        $table->string('category');
        $table->string('location');
        $table->text('description');
        $table->string('priority');
        $table->string('photo_path')->nullable();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
