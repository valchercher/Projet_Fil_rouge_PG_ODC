<?php

namespace App\Models;

use App\Models\Classe;
use App\Models\Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cours extends Model
{
    use HasFactory;
    protected $guarded=['id'];
    public function classes()
    {
        return $this->belongsToMany(Classe::class,'cours_classes','cours_id','classe_id','annee_scolaire_salle_id')->withPivot('nb_heure_global');
    }
    public function anneeScolaireSemestre()
    {
        return $this->belongsTo(AnneeScolaireSemestre::class);
    }

    public function anneeScolaireClasse()
    {
        return $this->belongsTo(AnneeScolaireClasse::class);
    }

    public function moduleUser()
    {
        return $this->belongsTo(ModuleUser::class);
    }
    public function sessions(){
        return $this->hasMany(Session::class);
    }
}
