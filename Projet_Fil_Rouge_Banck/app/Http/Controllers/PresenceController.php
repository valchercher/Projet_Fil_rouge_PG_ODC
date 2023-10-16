<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PresenceController extends Controller
{
    public function store(Request $request)
    {
        try
        {
            return DB::transaction(function () use($request) {
                $presence=Presence::create([
                    "user_id"=>$request->etudiant_id,
                    "session_id"=>$request->session_id,
                ]);
            });
            
        }catch(Exception $e)
        {
            return response()->json([
                "error"=>$e->getMessage(),
            ]);
        }
    }
}
