<?php

namespace App\Http\Controllers;


use Exception;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use App\Models\AnneeScolaireClasse;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;



class ExcelImportController extends Controller
{
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
                "message"=>"insertion avec succès",        
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
