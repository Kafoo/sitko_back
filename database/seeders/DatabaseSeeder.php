<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //\App\Models\User::factory(10)->create();
        //\App\Models\Project::factory(10)->create();

        \App\Models\Project::truncate();
        \App\Models\Event::truncate();
        \App\Models\Image::truncate();

        $projects = \App\Models\Project::factory(10)->create()
        ->each(function($projects){
            $projects->events()->save(\App\Models\Event::factory()->create());

            $odd = rand(0,3);
            if ($odd > 1) {
                $projects->image()->save(\App\Models\Image::factory()->create());
            }

        });
    }
}
