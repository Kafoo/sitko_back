<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	  \App\Models\Place::truncate();
        $projects = \App\Models\Place::factory(10)->create();
    }
}
