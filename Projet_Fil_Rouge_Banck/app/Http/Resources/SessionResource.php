<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\CoursResource;
use App\Http\Resources\SalleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
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
            "date"=>$this->date,
            "heure_debut"=>$this->heure_debut,
            "heure_fin"=>$this->heure_fin,
            "valider_session"=>$this->valider_session,
            "demande_annuler"=>$this->demande_annuler,
            "motif"=>$this->motif,
            "background"=>$this->background,
            "color"=>$this->color,
            "cours"=>CoursResource::make($this->cours),
            "salle"=>SalleResource::make($this->salle),
        ];
    }
}
