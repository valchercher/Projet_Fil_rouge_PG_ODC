<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\AnneeResource;
use App\Http\Resources\SemestreResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AnneeScolaireSemestreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'annee' => AnneeResource::make($this->annee),
            'semestre' => SemestreResource::make($this->semestre),
        ];
    }
}
