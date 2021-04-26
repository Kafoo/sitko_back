<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class PlaceResource extends JsonResource
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
            'author_id' => $this->author_id,
            'author' => $this->author,
            'name' => $this->name,
            'description' => $this->description,
            'location' => $this->location,
            'visibility' => $this->visibility,
            'image' => $this->image,
            'tags' => $this->tags,
            'link' => $this->getLinkState(),
            'projects_count' => $this->active_projects()->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
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
            'createEntity' => Gate::allows('createEntity', $this->resource),
        ];
    }

}
