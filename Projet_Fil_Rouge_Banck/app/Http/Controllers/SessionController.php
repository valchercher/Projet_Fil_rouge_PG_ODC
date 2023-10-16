<?php

namespace App\Http\Controllers;


use DateTime;
use Exception;
use App\Models\User;
use App\Models\Cours;
use App\Models\Salle;
use App\Models\Classe;
use App\Models\Module;
use App\Models\Session;
use App\Models\ModuleUser;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\AnneeScolaireClasse;
use App\Models\AnneeScolaireSemestre;
use App\Http\Resources\SessionResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\UniqueConstraintViolationException;

class SessionController extends Controller
{
    public function index(){
        $sessions=Session::all();
        return response()->json([
            "status"=>Response::HTTP_OK,
            "message"=>"planification de session de cours est créé avec succès",
            "data"=>[
                "sessions"=>SessionResource::collection($sessions),
            ]
        ]);
    }
    public static function convertirEnSecondes($heure)
    {
        $heureOb = explode(':', $heure);
        if ($heureOb) {
            $heures = $heureOb[0];
            $minutes =isset($heureOb[1])? $heureOb[1]:00;
            $secondes = isset($heureOb[2]) ? $heureOb[2] :00;
            return $heures * 3600 + $minutes * 60 + $secondes;
        }else{
            return 0;
        }
    }
    
    public function store(Request $request)
    {
        try
        {
            return DB::transaction(function () use($request){
            
                $anneeclasse=AnneeScolaireClasse::where([
                    'annee_id'=>$request->annee_id,'classe_id'=>$request->classe_id
                    ])->first();
                $anneesemestre=AnneeScolaireSemestre::where([
                    'annee_id'=>$request->annee_id,'semestre_id'=>$request->semestre_id
                    ])->first();
                $moduleuser=ModuleUser::where([
                    'module_id'=>$request->module_id,'user_id'=>$request->user_id
                    ])->first();
                            
                $coursProg=Cours::where('module_user_id',$moduleuser->id)
                    ->where('annee_scolaire_classe_id',$anneeclasse->id)
                    ->where('annee_scolaire_semestre_id',$anneesemestre->id)
                    ->first();
                $h_fin=$this->convertirEnSecondes($request->heure_fin);
                $h_debut=$this->convertirEnSecondes($request->heure_debut);
                $duree= $h_fin - $h_debut; 
                $heureCoursRestant=$coursProg->nb_heure_global - $duree; 
                if($request->salle_id===null)
                {
                }
                else
                {
                    $salleOccuper=Session::where('date',$request->date)
                                     ->where('salle_id',$request->salle_id)
                    ->get();
                    $salleCapacite=Salle::where('id',$request->salle_id)->first();
                    $classeEffectif=Classe::where('id',$request->classe_id)->first();
                    if($salleCapacite->nombre < $classeEffectif->effectif)
                    {
                        return response()->json([
                            "status"=>422,
                            "message"=>"la salle ne peut pas contenir cette classe"
                        ]);
                    }
                    foreach($salleOccuper as $salle)
                    {
                        if($salle->heure_debut <= $request->heure_debut && $request->heure_debut < $salle->heure_fin)
                        {
                            return response()->json([
                                "status"=>422,
                                "message"=>"a cet heure,on fait cours de session dans cette salle. salle occuper!!!!",
                            ],422);
                        }
                    }
                }
                if($duree <0)
                {
                    return response()->json([
                        "status"=>422,
                        "message"=>"l'heure de debut ne peur pas superieur ou égal à l'heure de fin"
                    ],422);
                }
                if($duree < 3600)
                {
                    return response()->json([
                        "status"=>422,
                        "message"=>"La durée du seission minimun c'est une heure"
                    ],422);
                }
                if($duree>14400){
                    return response()->json([
                        "status"=>422,
                        "message"=>"la durée du session  de cours est superieur a 4h"
                    ],422);
                }               
                $sessionExist=Session::where('cours_id',$coursProg->id)
                                        ->where('date',$request->date)
                ->get();
                foreach($sessionExist as $exist)
                {               
                    if($exist->heure_debut<=$request->heure_debut && $request->heure_debut < $exist->heure_fin)
                    { 
                    return response()->json([
                        "status"=>422,
                        "message"=>"le professeur a deja cours sur cette date ,meme heure et meme filiere",
                    ]);
                    }
                }
                if($coursProg->nb_heure_global=== $coursProg->ecouler)
                {
                    return response()->json([
                        "status"=>422,
                        "message"=>"le nombre d'heure global est terminer"
                    ],422);
                }
                if($duree + $coursProg->ecouler > $coursProg->nb_heure_global)
                {
                    return response()->json([
                        "status"=>422,
                        "message"=>"Le nombre d'heure global est insuffisant",
                    ],422);
                }
                $session=Session::create([
                    "date"=>$request->date,
                    "heure_debut"=>$request->heure_debut,
                    "heure_fin"=>$request->heure_fin,
                    "valider_session"=>$request->valider_session ? $request->valider_session : 0,
                    "demande_annuler"=>$request->demande_annuler ? $request->demande_annuler : 0,
                    "cours_id"=>$coursProg->id,
                    "salle_id"=>$request->salle_id ,
                    "background"=>$request->background,
                    "color"=>$request->color,
                ]);   
                
                $cours=Cours::find($coursProg->id)->increment('ecouler',$duree);
                return response()->json([
                    "status"=>Response::HTTP_OK,
                    "message"=>"planification de session de cours est créé avec succès",
                    "data"=>SessionResource::make($session),
                    ]);
               }
                          
            );
        }
        catch(UniqueConstraintViolationException $e)
        {
            return response()->json([
                "status"=>422,
                "errors"=>"cette combinaisson existe dèja",
            ],422);
        }
        catch(Exception $e)
        {
            report($e);
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function AnnulerSession(Request $request,$id)
    {
        try
        {
            $session=Session::find($id);
            if($session)
            {
                $h_d_s=$this->convertirEnSecondes($session->heure_debut);
                $h_f_s=$this->convertirEnSecondes($session->heure_fin);
                $duree=$h_f_s - $h_d_s;
                $cours=Cours::find($session->cours_id)->decrement('ecouler',$duree);
                $session->delete();
                return response()->json([
                    "status"=>200,
                    "message"=>"session de cours annuler avec succès",
                ]);
            }
        }
        catch(Exception $e)
        {
            return  response()->json([
                "error"=>$e->getMessage()
            ]);
        }
    }
    public function NePasAnnulerSeesion(Request $request,$id)
    {
        try
        {
            $sessionss=Session::find($id);
            if($sessionss)
            {
                $sessionss->update(['demande_annuler'=>'0']);
                return response()->json([
                    "status"=>200,
                    "message"=>"La demande d'annulation de la session de cours a été refusée."
                ]);
            }

        }catch(Exception $e)
        {
            return response()->json([
                "error"=>$e->getMessage(),
            ]);
        }
    }
    public function demandeAnnulerSession(Request $request,$id)
    {
        try
        {
            $demandeAnnuler=Session::find($id);
            if($demandeAnnuler)
            {
                $demandeAnnuler->update(['demande_annuler'=>'1']);
                $demandeAnnuler->update(['motif'=>$request->message]);
                
                return response()->json([
                    "status"=>200,
                    "message"=>"Demande d'annulation de cours est envoyé avec succès",
                ]);
            }
        }
        catch(Exception $e)
        {
            return  response()->json([
                "error"=>$e->getMessage()
            ]);
        }
    }
    public function validerSession(Request $request,$id)
    {
        try
        {

            $valider=Session::find($id);
            if($valider)
            {
                $valider->update(['valider_session'=>'1']);
                return response()->json([
                    "status"=>200,
                    "message"=>"session de cours valider avec succès",
                ]);
            }
        }catch(Exception $e)
        {
            return  response()->json([
                "error"=>$e->getMessage()
            ]);
        }
    }
    public function getDemandeAnnulation()
    {
        $sessions=Session::where('demande_annuler',1)->get();
        return response()->json([
            "status"=>200,
            "message"=>"all sassion salle",
            "data"=>[
                "sessions"=>SessionResource::collection($sessions),
            ]
        ]);
    }
    public function load(Request $request,$role,$cle=null,$libelle=null)
    {
        $ids=[];
        $param;
        if($cle==="salle")
        {
            $param="salle_id";
            $ids=Salle::where('nom',$libelle)->first()->pluck('id');   
        }
        else if($cle==="professeur")
        {
            $lib=explode(' ',$libelle);
            $profUser=0;
           if(is_numeric($libelle)){
            $profUser=User::where('id',$libelle)->where('role',$cle)->first();
           
           }else{
            $profUser=User::where('nom',$lib[1])->where('prenom',$lib[0])->where('role',$cle)->first();
           }
            $param="cours_id";
            $prof=ModuleUser::where('user_id',$profUser->id)->get()->pluck('id');
            $moduleUser=Cours::whereIn('module_user_id',$prof)->get();
            $ids= $moduleUser->pluck('id');
        }
        else if($cle==="classe")
        {
            $class=Classe::where('libelle',$libelle)->first();
            $classAnnee=AnneeScolaireClasse::where('classe_id',$class->id)->get()->pluck('id');
            $moduleUser=Cours::whereIn('annee_scolaire_classe_id',$classAnnee)->get();
            $ids= $moduleUser->pluck('id');
            $param="cours_id";
        }
        else if($cle==="module")
        {
            $module=Module::where('libelle',$libelle)->first();
            $moduleUser=ModuleUser::where('module_id',$module->id)->get()->pluck('id');
            $moduleUse=Cours::whereIn('module_user_id',$moduleUser)->get();
            $ids= $moduleUse->pluck('id');
            $param="cours_id";
        }
        else if($cle==="date")
        {
            $param="date";
            $ids[]=$libelle;      
        }
        else if($cle==="etat")
        {
            if($libelle==="terminer")
            {
                $ids[]=1;  
                $param="valider_session";
            }
            else if($libelle==="en cours")
            {
                $ids[]=0;  
                $param="valider_session";
            }
            else if($libelle==="annuler")
            {
                $ids[]=0;  
                $param="demande_annuler";
            }
            else if($libelle==="valider")
            {
                $ids[]=1;  
                $param="demande_annuler";
            }  
        }
        if(!$ids && ($role=="RP"||$role==="attache")){
            $sessionSearch=Session::all();  
        }else{

            $sessionSearch=Session::whereIn($param,$ids)->get();
        }      
        return response()->json([
            "status"=>200,
            "message"=>"all sassion salle",
            "data"=>[
                "sessions"=>SessionResource::collection($sessionSearch),
            ]
        ]);
    }
}
