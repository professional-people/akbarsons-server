<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicineTypesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'type'          => $this->type,
            'description'   => $this->description,
            'status'        => $this->status
        ];
    }
}
