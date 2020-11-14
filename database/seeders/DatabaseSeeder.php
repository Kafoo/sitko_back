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

        $projects = \App\Models\Project::factory(10)->create()
        ->each(function($projects){
            $projects->events()->save(\App\Models\Event::factory()->create());

        });
    }
}
