<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\JsonResource;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Relation::morphMap([
            'project' => 'App\Models\Project',
            'event' => 'App\Models\Event',
            'place' => 'App\Models\Place',
            'user' => 'App\Models\User'
        ]);

        JsonResource::withoutWrapping();

    }
}
