<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \App\Models\Project::factory(10)->create()
        ->each(function($projects){
            $projects->caldates()->save(\App\Models\Caldate::factory()->create());

            $odd = rand(0,3);
            if ($odd > 1) {
                $projects->image()->save(\App\Models\Image::factory()->create());
            }

        });

    }
}
