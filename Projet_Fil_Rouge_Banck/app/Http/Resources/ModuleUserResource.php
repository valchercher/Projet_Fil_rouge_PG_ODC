<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ModuleResource;
use App\Http\Resources\ProfesseurResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleUserResource extends JsonResource
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
            'user' => ProfesseurResource::make($this->user),
            'module' => ModuleResource::make($this->module),
        ];
    }
}
