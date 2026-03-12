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
        
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        $table->string('title'); 
        $table->text('description'); 
        $table->string('apartment_number')->nullable(); 
        
        
        $table->string('status')->default('en_attente'); 
        
        $table->text('admin_comment')->nullable(); 
        $table->string('priority')->default('normale'); 
        
        $table->timestamps(); 
    });
}
};
