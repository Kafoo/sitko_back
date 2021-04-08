<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'place' => $this->place,
            'linked_places' => $this->getLinkedPlaces(),
            'linked_users' => $this->getLinkedUsers(),
            'tags' => $this->tags
        ];
    }

}
