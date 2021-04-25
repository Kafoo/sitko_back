<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\GlobalModel;

class Visibility extends GlobalModel
{
    use HasFactory;

    protected $table = 'visibilities';

}
