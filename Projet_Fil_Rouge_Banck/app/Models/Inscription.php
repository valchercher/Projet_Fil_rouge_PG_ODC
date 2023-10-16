<?php

namespace App\Models;

use App\Models\User;
use App\Models\AnneeScolaireClasse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inscription extends Model
{
    use HasFactory;
    protected $guarded=['id'];
    public function anneclass()
    {
        return $this->hasMany(AnneeScolaireClasse::class);
    }
    public function etudiant()
    {
        return $this->hasMany(User::class);
    }
}
