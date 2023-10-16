<?php

namespace App\Models;

use App\Models\User;
use App\Models\Annee;
use App\Models\Classe;
use App\Models\ModuleUser;
use App\Models\Inscription;
use App\Models\AnneeScolaireClasse;
use App\Models\AnneeScolaireSemestre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnneeScolaireClasse extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function annee()
    {
        return $this->belongsTo(Annee::class);
    }
    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }
    public function etudiants()
    {
        return $this->belongsToMany(User::class,'inscriptions');
    }
}
