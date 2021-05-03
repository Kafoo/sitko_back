<?php

namespace App\Http\Resources;

use App\Models\Place;
use App\Models\User;
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
            'read_at' => $this->read_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'type' => $this->data['type'],
            'link' => $this->data['vue_link'],
            'message' => $this->data['message'],
            'specifics' => $this->getSpecifics()
        ];
    }

    private function getSpecifics()
    {
        if ($this->data['type'] === "link_request" ||
            $this->data['type'] === "link_confirmation") {
            return [
                'requesting' => $this->getRequesting(),
                'requesting_type' => $this->data['requesting_type'],
                'requested' => $this->getRequested(),
                'requested_type' => $this->data['requested_type'],
                'state' => $this->getState(),
            ];
        } else {
            return null;
        }
    }

    private function getState()
    {
      if (array_key_exists('state', $this->data) ) {
        return $this->data['state'];
      }else{
        return null;
      }
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

        if ($this->data['requested_type'] === 'user') {
            return new UserResource(User::find($this->data['requested_id']));
        }elseif ($this->data['requested_type'] === 'place'){
            return new PlaceResource(Place::find($this->data['requested_id']));
        }
    }

}
