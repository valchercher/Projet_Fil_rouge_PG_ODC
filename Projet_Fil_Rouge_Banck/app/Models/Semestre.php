<?php

namespace App\Models;

use App\Models\Annee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Semestre extends Model
{
    use HasFactory;
    public function annees()
    {
        return $this->belongsToMany(Annee::class, 'annee_scolaire_semestres')->withTimestamps();
    }
}
