<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cours;
use App\Models\Salle;
use App\Models\Classe;
use App\Models\Module;
use App\Models\ModuleUser;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use PHPUnit\Event\Code\Throwable;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CoursRequest;
use App\Models\AnneeScolaireClasse;
use App\Http\Resources\CoursResource;
use App\Http\Resources\SalleResource;
use App\Models\AnneeScolaireSemestre;
use App\Http\Resources\ClasseResource;
use App\Http\Resources\ModuleResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ProfesseurResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\UniqueConstraintViolationException;

class CoursController extends Controller
{
    public function index(Request $request,$index=null)
    {
        $cours=Cours::paginate($index?$index:3);
        $module=Module::with('users')->get();
        $professeur=User::where(['role'=>'professeur','etat'=>'1'])->get();
        $classe=Classe::where('etat','1')->get();
        $salle=Salle::where('etat','1')->get();
        return response()->json([
            "status"=>Response::HTTP_OK,
            "message"=>"All data",
            "pagination"=>[
                "page_current"=>$cours->currentPage(),
                "per_page"=>$cours->perPage(),
                "total"=>$cours->total()
            ],
            "data"=>[
            "cours"=>CoursResource::collection($cours),
            "modules"=>ModuleResource::collection($module),
            "professeurs"=>ProfesseurResource::collection($professeur),
            "classes"=>ClasseResource::collection($classe),
            "salles"=>SalleResource::collection($salle),
            ]
        ]);
    }
    public function store(CoursRequest $request)
    {
       try {
        return DB::transaction(function () use($request) {
        
            $classe_ouvert=AnneeScolaireClasse::where([
                "classe_id"=>$request->classe_id,"annee_id"=>$request->annee_id
                ])->first();
            $module_user=ModuleUser::where([
                "module_id"=>$request->module_id,"user_id"=>$request->user_id
                ])->first();
            $annee_semestre=AnneeScolaireSemestre::where([
                'annee_id'=>$request->annee_id,'semestre_id'=>$request->semestre_id
                ])->first();
            $request->validate([
                ['module_user_id'], Rule::unique('cours')
                ->where(function ($query) use ($request,$classe_ouvert,$annee_semestre) {
                return $query->where('annee_scolaire_classe_id', $classe_ouvert->id)
                             ->where('annee_scolaire_semestre_id', $annee_semestre->id)
                             ->where('nb_heure_global', $request->input('nb_heure_global'));
                  }),
            ]);
            $coursExist=Cours::where('module_user_id',$module_user->id)
                                ->where('annee_scolaire_classe_id', $classe_ouvert->id)
                                ->where('annee_scolaire_semestre_id', $annee_semestre->id)->first();
            if($coursExist){
                return response()->json([
                    "message"=>"planification cours existe dèja"
                ]);
            }
            $cours =Cours::create([
                "nb_heure_global"=>$request->nb_heure_global *3600,   
                "annee_scolaire_classe_id"=>$classe_ouvert->id,
                "module_user_id"=>$module_user->id,
                "annee_scolaire_semestre_id"=>$annee_semestre->id,
            ]);
            return response()->json([
                "status"=>Response::HTTP_OK,
                "message"=>"cours ajouter avec succès",
                "data"=>CoursResource::make($cours)
            ]);
        });
       }
       catch (UniqueConstraintViolationException $e) {
        return response()->json([
            'errors' => 
                'Cette combinaison existe déjà.'
            ], 422);
        } 
        catch (\Exception $e) {
            return response()->json([
                'error' => $e
            ], 500);
        }       
    }
    public function load(Request $request,$role,$index=null,$cle=null,$libelle=null)
    {   
        $ids=[];
        $param="";
        if($cle==="module")
        {
            $module=Module::where('libelle',$libelle)->first();
            $ids=ModuleUser::where('module_id',$module->id)->pluck('id')->toArray();
            $param="module_user_id";
        }
        if($cle==="professeur")
        {
            $lib=explode(' ',$libelle);
            
            if(is_numeric($libelle)){
                $module=User::where('id',$libelle)->where('role',$cle)->first();
                $ids=ModuleUser::where('user_id',$module->id)->pluck('id')->toArray(); 
            }else{
            $module=User::where('nom',$lib[1])->where('prenom',$lib[0])->where('role',$cle)->first();
            $ids=ModuleUser::where('user_id',$module->id)->pluck('id')->toArray();
            }
            $param="module_user_id";
        }
        if($cle==="classe")
        {
            $class=Classe::where('libelle',$libelle)->first();
            $ids=AnneeScolaireClasse::where('classe_id',$class->id)->pluck('id');
            $param="annee_scolaire_classe_id";
        }
        if($cle==="etat")
        {
            $param="etat";
            if($libelle==="terminer")
            {
                $ids=[1];
            }
            if($libelle==="en cours")
            {
                $ids=[0];
            }
        }
        if(!$ids && ($role==="RP" ||$role==="attache")){
            $all=Cours::paginate($index?$index:3);
        }
       else{
        $all=Cours::whereIn($param,$ids)->paginate($index?$index:3);
       }
        return response()->json([
            "status"=>200,
            "message"=>"all",
            "pagination"=>[
                "page_current"=>$all->currentPage(),
                "per_page"=>$all->perPage(),
                "total"=>$all->total()
            ],
            "data"=>[
            "cours"=>CoursResource::collection($all),
            ]
        ]);
    }
}
