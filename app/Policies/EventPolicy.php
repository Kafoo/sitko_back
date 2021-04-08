<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy extends AuthorablePolicy
{
    use HandlesAuthorization;

}
