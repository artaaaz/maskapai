<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PassengerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'full_name'       => $this->full_name,
            'gender'          => $this->gender,
            'birth_date'      => $this->birth_date->format('Y-m-d'),
            'passport_number' => '****-' . substr($this->passport_number, -4), // MASKING
            'seat_number'     => $this->seat_number,
        ];
    }
}