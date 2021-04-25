<?php

namespace App\Traits;
use Illuminate\Support\Facades\DB;

trait Relationable
{

	public function places_relations_1()
	{
    return $this->morphToMany('App\Models\Place', 'first', 'relationships', 'first_id', 'second_id')->withPivot('state');
	}

	public function places_relations_2()
	{
    return $this->morphToMany('App\Models\Place', 'second', 'relationships', 'second_id', 'first_id')->withPivot('state');
	}

	public function users_relations_1()
	{
    return $this->morphToMany('App\Models\User', 'first', 'relationships', 'first_id', 'second_id')->withPivot('state');
	}

	public function users_relations_2()
	{
    return $this->morphToMany('App\Models\User', 'second', 'relationships', 'second_id', 'first_id')->withPivot('state');
	}

  public function linkedPlaces()
  {
   return $this->places_relations_1->merge($this->places_relations_2);
  }

  public function linkedUsers()
  {
   return $this->users_relations_1->merge($this->users_relations_2);
  }

  public function getLink($related)
  {

    $relation = DB::table('relationships')
        ->where(function ($query) use ($related) {
            $query->where('first_id', $this->id)
                ->where('second_id', $related->id);
        })
        ->orWhere(function($query) use ($related) {
            $query->where('second_id', $this->id)
                ->where('first_id', $related->id);	
        });

    return $relation;
  }

  public function getLinkState(){

    $link = $this->getLink(auth()->user())->first();

    if ($link) {
      if ($link->state === "pending") {
        if ($link->first_id === auth()->user()->id) {
          return "requesting";
        } else{
          return "requested";
        }
      } else {
        return $link->state;
      }
    }else{
      return null;
    }
  }

  public function isLinked($related){
  
    $isLinked = false;

    if ($this->getLink($related)->count() > 0) {
      $isLinked = true;
    }

    return $isLinked;

  }

  public function clearRelationships(){
  
    $this->places_relations_1()->detach();
    $this->places_relations_2()->detach();
    $this->users_relations_1()->detach();
    $this->users_relations_2()->detach();
  
  }

}
