<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\FiliereResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ClasseResource extends JsonResource
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
            "libelle"=>$this->libelle,
            "filiere"=>FiliereResource::make($this->filiere),
            "etat"=>$this->etat,
            "effectif"=>$this->effectif
        ];
    }
}
