<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    public function up(): void
{
    Schema::create('providers', function (Blueprint $table) {
        $table->id();
        $table->string('name'); 
        $table->string('specialty'); 
        $table->string('phone');
        $table->string('email');
        $table->boolean('is_emergency')->default(false);
        $table->timestamps();
    });
}
}
