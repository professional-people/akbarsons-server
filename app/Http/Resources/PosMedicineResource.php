<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PosMedicineResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'medicine_id'       => $this->medicine_id,
            'medicine_name'     => $this->getMedicine->medicine_name ?? "",
            'batch_no'          => $this->batch_no,
            'unit_sale_price'   => $this->unit_sale_price,
            'available_qty'     => $this->available_qty
        ];
    }
}
