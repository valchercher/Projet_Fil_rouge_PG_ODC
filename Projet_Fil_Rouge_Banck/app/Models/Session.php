<?php

namespace App\Models;

use App\Models\User;
use App\Models\Cours;
use App\Models\Salle;
use App\Models\CoursClasseSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Session extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded=['id'];
    public function coursclassesessions(){
        return $this->belongsToMany(CoursClasseSession::class,'cours_classe_sessions','session_id','cours_classe_id');
    }
    public function salle(){
        return $this->belongsTo(Salle::class);
    }
    public function cours(){
        return $this->belongsTo(Cours::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class,'absences')->withPivot('date');
    }

}
