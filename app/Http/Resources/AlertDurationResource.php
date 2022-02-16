<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class AlertDurationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'duration'  => $this->duration,
            'type'      => Str::plural($this->duration_type, $this->duration)
        ];
    }
}
