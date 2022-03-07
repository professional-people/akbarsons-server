<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class StockResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'medicine_id'           => $this->medicine_id,
            'medicine_name'         => $this->getMedicine->medicine_name ?? null,
            'batch_no'              => $this->batch_no,
            'expiry_date'           => Carbon::parse($this->expiry_date)->format("M d, Y"),
            'is_expiry_alert'       => ($this->is_expiry_alert == 1) ? "Yes" : "No",
            'alert_duration_id'     => $this->alert_duration_id,
            'alert_duration'        => !empty($this->alert_duration_id) ? $this->getAlertDuration->duration . " " . Str::plural($this->getAlertDuration->duration_type, $this->getAlertDuration->duration) : null,
            'stock_type_id'         => $this->stock_type,
            'stock_type'            => $this->getStockType->title ?? null,
            'purchase_price'        => $this->purchase_price,
            'unit_purchase_price'   => $this->unit_purchase_price,
            'sale_price'            => $this->sale_price,
            'unit_sale_price'       => $this->unit_sale_price,
            'total_qty'             => collect($this->getQuantity)->sum('total_quantity'),
            'status'                => $this->status
        ];
    }
}
