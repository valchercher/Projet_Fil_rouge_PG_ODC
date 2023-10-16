<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ModuleResource;
use App\Http\Resources\ProfesseurResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CoursClasseResource extends JsonResource
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
            "nombre_heure_global"=>$this->pivot->nb_heure_global,
            "user"=>ProfesseurResource::make($this->user),
            "module"=>ModuleResource::make($this->module)
        ];
    }
}
