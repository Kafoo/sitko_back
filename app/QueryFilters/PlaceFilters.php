<?php

namespace App\QueryFilters;

/**
 * Filter records based on query parameters.
 *
 */
class PlaceFilters extends LinkableFilters
{
    /**
     * Filter records based on the query parameter "user"
     * 
     * @return void
     */
    public function user()
    {
        $this->query->where('author_id', auth()->user()->id);
    }
}
