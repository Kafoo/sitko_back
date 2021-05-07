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
            'bio' => $this->bio,
            'expectations' => $this->expectations,
            'user_type' => $this->user_type,
            'home_type' => $this->home_type,
            'contact_infos' => $this->contact_infos ?? json_decode('{}'),
            'preferences' => $this->preferences ?? json_decode('{}'),
            'image' => $this->image,
            'email_verified_at' => $this->email_verified_at,
            'places' => PlaceResource::collection($this->places),
            'linked_places' => $this->linkedPlaces(),
            'linked_users' => $this->linkedUsers(),
            'tags' => $this->tags
        ];
    }

}
