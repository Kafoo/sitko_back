<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GlobalRequest extends FormRequest
{
    protected $visibility = ['visibility' => 'required|numeric'];
}
