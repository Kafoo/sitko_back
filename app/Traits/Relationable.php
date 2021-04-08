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

  public function getLinkedPlaces()
  {
   return $this->places_relations_1->merge($this->places_relations_2);
  }

  public function getLinkedUsers()
  {
   return $this->users_relations_1->merge($this->users_relations_2);
  }

  public function getLink($related)
  {

    $related = auth()->user();

    return DB::table('relationships')
        ->where(function ($query) use ($related) {
            $query->where('first_id', $this->id)
                ->where('second_id', $related->id);
        })
        ->orWhere(function($query) use ($related) {
            $query->where('second_id', $this->id)
                ->where('first_id', $related->id);	
        });
  }

  public function getLinkState(){

    $link = $this->getLink(auth()->user());

    if ($link->count() > 0) {
      return $link->first()->state;
    }else{
      return null;
    }
  }


}