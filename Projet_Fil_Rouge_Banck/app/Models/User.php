<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Module;
use App\Models\Session;
use App\Models\Inscription;
use Laravel\Sanctum\HasApiTokens;
use App\Models\AnneeScolaireClasse;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    const HEADINGS=[
        'nom',
        'email',
        'password',
        'prenom',
        'role',
        'date_naissance'
    ];
    protected $fillable = [
        'nom',
        'email',
        'password',
        'prenom',
        'role',
        'grade',
        'specialite',
        'date_naissance'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function modules(){
        return $this->belongsToMany(Module::class,'module_users');
    }
    public function anneeclasses()
    {
        return $this->belongsToMany(AnneeScolaireClasse::class,'inscriptions');
    }
    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }
    public function sessions()
    {
        return $this->belongsToMany(Session::class,'absences')->withPivot('date');
    }

}
