<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incident extends Model
{
    
   protected $fillable = [
    'title', 
    'category',
    'location',
    'description',
    'priority',
    'photo_path'
];

    // Relation : Un incident appartient à un utilisateur (le locataire)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}