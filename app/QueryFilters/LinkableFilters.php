<?php

namespace App\QueryFilters;

use Cerbero\QueryFilters\QueryFilters;

/**
 * Filter records based on query parameters.
 *
 */
class LinkableFilters extends QueryFilters
{
    /**
     * Filter records based on the query parameter "linked"
     * 
     * @return void
     */
    public function linked()
    {
        $this->query->whereHas('users_relations_1', function($q) {
            $q->where('relationships.state', 'confirmed')
                ->Where('second_id', auth()->user()->id)
                ->Where('second_type', 'user');

        })->orWhereHas('users_relations_2', function($q) {
            $q->where('relationships.state', 'confirmed')
                ->Where('first_id', auth()->user()->id)
                ->Where('first_type', 'user');
        });
    }
}
