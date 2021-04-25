<?php

namespace App\Http\Resources;

use App\Models\Place;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'type' => $this->data['type'],
            'requesting' => $this->getRequesting(),
            'requesting_type' => $this->data['requesting_type'],
            'requested' => $this->getRequested(),
            'requested_type' => $this->data['requested_type'],
            'requested_at' => $this->data['requested_at'],
            'read_at' => $this->read_at,
            'state' => $this->data['state'],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }


    private function getRequesting()
    {
        $model_name = '\\App\\Models\\' . ucfirst($this->data['requesting_type']);
        $model = $model_name::find($this->data['requesting_id']);
        $resource = 'App\\Http\\Resources\\' . ucfirst($this->data['requesting_type']) . 'Resource';

        return new $resource($model);
    }

    private function getRequested()
    {

        $lol = Place::find($this->data['requested_id']);


        if ($this->data['requested_type'] === 'user') {
            return new AuthResource(auth()->user());
        }elseif ($this->data['requested_type'] === 'place'){
            return new PlaceResource(Place::find($this->data['requested_id']));
        }
    }

}
