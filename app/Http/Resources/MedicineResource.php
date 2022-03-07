<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicineResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'                    =>  $this->id,
            'medicine_type_id'      =>  $this->type_id ?? "-",
            'medicine_type'         =>  $this->getMedicineType->type ?? "-",
            'medicine_company_id'   =>  $this->company_id ?? "-",
            'medicine_company'      =>  $this->getMedicineCompany->title ?? "-",
            'vendor_id'             =>  $this->vendor_id,
            'vendor_name'           =>  $this->getVendor->vendor_name ?? "-",
            'medicine_name'         =>  $this->medicine_name,
            'chemical_name'         =>  $this->chemical_name,
            'medicine_description'  =>  $this->medicine_description ?? "-",
            'status'                =>  $this->status
        ];
    }
}
