<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use Illuminate\Http\Request;

class AbsenceController extends Controller
{
    public function  store(Request $request)
    {
        try
        {
            return DB::transaction(function () use($request){
                $absence=Absence::create([
                    "user_id"=>$request->etudiant_id,
                    "session_id"=>$request->session_id
                ]);
                return response()->json([
                    "status"=>200,
                    "message"=>"absence",
                ]);
            });
           }catch(Exception $e)
           {
            return response()->json([
                "status"=>200,
                "error"=>$e->getMessage(),
            ]);
           }

        
    }
}
