<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
    use HasFactory;
    protected $fillable=['module_id'];
    public function users(){
        return $this->belongsToMany(User::class,'module_users');
    }
}
