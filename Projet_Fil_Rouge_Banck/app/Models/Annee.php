<?php

namespace App\Models;

use App\Models\Classe;
use App\Models\Semestre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Annee extends Model
{
    use HasFactory;
    protected $guarded=['id'];
    public function semestres()
    {
        return $this->belongsToMany(Semestre::class, 'annee_scolaire_semestres')->withTimestamps();
    }

    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'annee_scolaire_classes')->withTimestamps();
    }
}
