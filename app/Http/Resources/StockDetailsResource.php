<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class StockDetailsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                        => $this->id,
            'stock_id'                  => $this->stock_id,
            'quantity'                  => $this->quantity,
            'quantity_in_stock_type'    => $this->quantity_in_stock_type,
            'total_quantity'            => $this->total_quantity,
            'status'                    => $this->status,
            'created_at'                => Carbon::parse($this->created_at)->format("d M, Y")
        ];
    }
}
