<?php

namespace App\Models;

use App\Models\User;
use App\Models\Cours;
use App\Models\Module;
use App\Models\ModuleUser;
use App\Models\AnneeScolaireClasse;
use App\Models\AnneeScolaireSemestre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModuleUser extends Model
{
    use HasFactory;
    // protected $fillable=[
    //     'module_id',
    //     'user_id'
    // ];
    protected $guarded=['id'];
    

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function cours()
    {
        return $this->hasMany(Cours::class, 'module_user_id');
    }
}
