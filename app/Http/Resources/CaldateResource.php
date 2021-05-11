<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CaldateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $childModel = "App\Http\Resources\\".ucfirst($this->child_type)."Resource";

        return [
            'child_id' => $this->child_id,
            'child_type' => $this->child_type,
            'child' => new $childModel($this->child),
            'end' => $this->end,
            'id' => $this->id,
            'place_id' => $this->place_id,
            'start' => $this->start,
            'timed' => $this->timed,
            'id' => $this->id,
        ];
    }
}
