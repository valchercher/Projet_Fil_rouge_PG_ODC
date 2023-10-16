<?php

namespace App\Models;

use App\Models\User;
use App\Models\Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absence extends Model
{
    use HasFactory;
    protected $guarded=['id'];
    
    public function user()
    {
        return $this->hasMany(User::class);
    }
    public function session()
    {
        return $this->hasMany(Session::class);
    }
}
