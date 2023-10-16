<?php

namespace App\Models;

use App\Models\Annee;
use App\Models\Cours;
use App\Models\Filiere;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classe extends Model
{
    use HasFactory,SoftDeletes;
    public function cours()
    {
        return $this->belongsToMany(Cours::class,'cours_classes');
    }
    public function filiere(){
        return $this->belongsTo(Filiere::class);
    }
   public function annees()
    {
        return $this->belongsToMany(Annee::class, 'annee_scolaire_classes')->withTimestamps();
    }
}
