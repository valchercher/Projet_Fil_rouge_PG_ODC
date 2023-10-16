<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CoursRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            
            'nb_heure_global' => 'required|integer',
            'classe_id' => 'required|integer|exists:classes,id',
            'user_id' => 'required|integer|exists:users,id',
            'annee_id'=>'required|integer|exists:annees,id',
            'module_id'=>'required|integer|exists:modules,id',
            'semestre_id' => 'required|integer|exists:semestres,id',

        ];
    }
    public function messages(){
        return [
            'nb_heure_global.required'=>"le nombre d'heure global doit etre requi",
            'nb_heure_global.integer'=>'le nombre d\'heure global doit etre un nombre',
            'classe_id.required'=>"la classe doit etre fourni",
            "classe_id.exists"=>"l'id de la classe doit exister",
            'user_id.required'=>"le professeur doit etre fourni",
            "user_id.exists"=>"l'id du professeur doit exister",
            'annee_id.required'=>"l'annee doit etre fourni",
            "annee_id.exists"=>"l'id de l'annee doit exister",
            'module_id.required'=>"le module doit etre fourni",
            "module_id.exists"=>"l'id du module doit exister",
            'semestre_id.required'=>"le semestre doit etre fourni",
            "semestre_id.exists"=>"l'id du semestre doit exister",

        ];
    }
}
