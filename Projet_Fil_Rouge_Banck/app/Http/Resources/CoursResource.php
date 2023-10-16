<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ModuleUserResource;
use App\Http\Resources\CoursClasseResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AnneeScolaireClasseResource;
use App\Http\Resources\AnneeScolaireSemestreResource;

class CoursResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            'nb_heure_global' => $this->convertirEnHeure($this->nb_heure_global),
            "ecouler"=>$this->convertirEnHeure($this->ecouler),
            'etat' => $this->etat,
            'annee_scolaire_semestre' => AnneeScolaireSemestreResource::make($this->anneeScolaireSemestre),
            'annee_scolaire_classe' => AnneeScolaireClasseResource::make($this->anneeScolaireClasse),
            'module_user' => ModuleUserResource::make($this->moduleUser),
        ];
    }
    public static function convertirEnHeure($secondes)
    {
        $heures = $secondes / 3600;
        $minutes = ($secondes % 3600) / 60;
        $secondesRestantes = $secondes % 60;
        return sprintf("%02d:%02d:%02d", $heures, $minutes, $secondesRestantes);
    }
}
