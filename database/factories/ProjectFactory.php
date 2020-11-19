<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $goats = array(null, null, null,
            'https://res.cloudinary.com/dyigive9u/image/upload/v1605780464/vcwhbubluaa8qn8ky83i.jpg',
            'https://res.cloudinary.com/dyigive9u/image/upload/v1605780589/fklpmx1pbbafpspgn4vo.jpg',
            'https://res.cloudinary.com/dyigive9u/image/upload/v1605780620/larxbohavlepmx9le4yn.jpg');

        $img = $goats[array_rand($goats)];

        if ($img !== null) {
            $parts = explode('upload/', $img);
            $img_medium = $parts[0].'upload/t_medium/'.$parts[1];
            $img_thumb = $parts[0].'upload/t_thumb/'.$parts[1];
        }else{
            $img_medium = null;
            $img_thumb = null;            
        }

        return [
            'title' => $this->faker->sentence(2, true),
            'type' => $this->faker->randomElement(['idée', 'commun', 'perso']),
            'description' => $this->faker->paragraph(rand(2,7)),
            'img' => $img,
            'img_medium' => $img_medium,
            'img_thumb' => $img_thumb,
        ];
    }
}
