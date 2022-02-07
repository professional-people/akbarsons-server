<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicineResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'                    =>  $this->id,
            'medicine_type'         =>  $this->getMedicineType->type ?? "-",
            'medicine_company'      =>  $this->getMedicineCompany->title ?? "-",
            'medicine_name'         =>  $this->medicine_name,
            'chemical_name'         =>  $this->chemical_name,
            'medicine_description'  =>  $this->medicine_description ?? "-",
            'status'                =>  $this->status
        ];
    }
}
