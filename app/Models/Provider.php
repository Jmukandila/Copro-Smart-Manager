<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    public function up(): void
{
    Schema::create('providers', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Nom de l'entreprise (ex: SOS Plomberie)
        $table->string('specialty'); // Plomberie, Électricité, Ascenseur
        $table->string('phone');
        $table->string('email');
        $table->boolean('is_emergency')->default(false); // Est-ce qu'ils interviennent 24h/24 ?
        $table->timestamps();
    });
}
}
