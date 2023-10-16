<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ProfesseurResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
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
            "professeur"=>ProfesseurResource::collection($this->users)
        ];
    }
}
