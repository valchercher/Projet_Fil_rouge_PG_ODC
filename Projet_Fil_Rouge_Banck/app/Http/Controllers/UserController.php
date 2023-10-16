<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Cours;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use PHPUnit\Event\Code\Throwable;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\ProfesseurResource;
use App\Http\Requests\AuthentificationRequest;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function store(Request $request)
    {
        try 
        {
            return \DB::transaction(function () use($request) {
                $user=User::create([
                    "nom"=>$request->nom,
                    "prenom"=>$request->prenom,
                    "email"=>$request->email,
                    "password"=>$request->password,
                    "role"=>$request->role,
                    "specialite"=>$request->specialite,
                    "grade"=>$request->grade,
                    "date_naissance"=>$request->date_naissance,
                ]);
                return response()->json([
                    "status"=>Response::HTTP_OK,
                    "message"=>"user est créé avec succès",
                    "data"=>$user
                ]);
            });
        }
        catch(Trowable $e)
        {
            report($e);
            return false;
        }
    }
    public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
    public function login(AuthentificationRequest $request)
    {
        try
        {
            if(!(Auth::attempt(['email' => $request->email, 'password' => $request->password])))
            {
                response()->json([
                    "status"=>Response::HTTP_NO_CONTENT,
                    "message"=>"Mot de passe ou email incorrecte",
                ],422);
            }
            $user=Auth::user();
            $token=$user->createToken('MON_TOKEN')->plainTextToken;
            return response()->json([
                "status"=>Response::HTTP_OK,
                "message"=>"connecter avec succès",
                "data"=>[
                    "token"=>$token,
                    "data"=>ProfesseurResource::make($user)
                ]
            ]);
        }catch(Exception $e)
        {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }
    public function logout()
    {
        $user=Auth::user();
        $user->currentAccessToken()->delete();
        return response()->json([
            "status"=>422,
            "message"=>"vous avez deconnecter avec succes",
        ]);
    }
    public function import(Request $request)
    { 
        try
        {
            request()->validate([
                'users' => 'required|mimes:xlsx,xls|max:2048'
            ]);
            // $anneeclasse=AnneeScolaireClasse::where(['annee_id'=>$request->annee_id,'classe_id'=>$request->classe_id])->first();    
            $excel=Excel::import(new UsersImport, $request->file('users'));  
            return response()->json([
                "status"=>Response::HTTP_OK,
                "message"=>" les étudiants sont inscris  avec succès", 
                "data"=>[
                    "data"=>$excel,
                ]       
            ]);
        } 
        catch (Exception $e)
        {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }
}
