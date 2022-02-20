<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return
            [
                'id'                    => $this->id,
                'vendor_name'           => $this->vendor_name,
                'vendor_description'    => $this->vendor_description,
                'status'                => $this->status,
            ];
    }
}
