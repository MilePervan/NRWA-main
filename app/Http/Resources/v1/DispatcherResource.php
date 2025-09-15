<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DispatcherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'phone' => $this->phone,
            'manager_id' => $this->manager_id, 
            'location_id' => $this->location_id, 
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
