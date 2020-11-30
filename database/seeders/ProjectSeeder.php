<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Http\Controllers\ProjectController;


class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

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
