<?php

namespace App\Models;

use App\Models\Annee;
use App\Models\Semestre;
use App\Models\ModuleUser;
use App\Models\AnneeScolaireClasse;
use App\Models\AnneeScolaireSemestre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnneeScolaireSemestre extends Model
{
    use HasFactory;
    protected $fillable = [];

    public function annee()
    {
        return $this->belongsTo(Annee::class);
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }
}
