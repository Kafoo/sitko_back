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
