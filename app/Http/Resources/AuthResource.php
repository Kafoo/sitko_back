<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'image' => $this->image,
            'email_verified_at' => $this->email_verified_at,
            'places' => PlaceResource::collection($this->places),
            'linked_places' => $this->linkedPlaces(),
            'linked_users' => $this->linkedUsers(),
            'tags' => $this->tags
        ];
    }

}
