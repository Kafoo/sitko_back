<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

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
            'name' => $this->name,
            'last_name' => $this->last_name,
            'image' => $this->image,
            'tags' => $this->tags,
            'bio' => $this->bio,
            'expectations' => $this->expectations,
            'user_type' => $this->user_type,
            'home_type' => $this->home_type,
            'contact_infos' => $this->contact_infos ?? json_decode('{}'),
            'link' => $this->getLinkState(),
            'can' => $this->permissions(),
        ];
    }

    /**
     * Returns the permissions of the resource.
     *
     * @return array
     */
    protected function permissions()
    {
        return [
            'update' => Gate::allows('update', $this->resource),
            'link' => Gate::allows('link', $this->resource),
        ];
    }

}
