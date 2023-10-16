<?php

namespace App\Imports;

use DateTime;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Annee;
use App\Models\Classe;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\AnneeScolaireClasse;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;

class UsersImport implements ToModel
{
    // private $anneeclasses;

    // public function __construct(string $anneeclasses)
    // {
       
    //     $this->anneeclasses = $anneeclasses;
    // }
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
     {  
        dd($row["Email"]);
        
        $exp=explode('/',$row['Date_naissance']);
        $date=$exp[0].'-'.$exp[1].'-'.$exp[2];
        return DB::transaction(function () use($row,$date) {
        $condition=['email'=>$row['Email']];
        $user= User::firstOrCreate($condition,[
               'prenom' => $row['Prenom'],
               'nom' => $row['Nom'],
               'email'=>$row['Email'],
               'date_naissance'=>$date,
               'role'=>$row['Role'],
               'password' => bcrypt($row['Mot_de_passe']),
        ]);
        $classes=Classe::where('libelle',$row['Classe'])->first();
        $annee=Annee::where('libelle',$row['Annee_en_cours'])->where('etat','1')->first();
        $anneeclasses=AnneeScolaireClasse::where(['classe_id'=>$classes->id,'annee_id'=>$annee->id])->first();
           $user->anneeclasses()->attach($anneeclasses);
           return $user;
            
        });
    }
}
