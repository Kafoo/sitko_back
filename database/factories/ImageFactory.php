<?php

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Image::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $goats = array(
            'https://res.cloudinary.com/dyigive9u/image/upload/v1605780464/vcwhbubluaa8qn8ky83i.jpg',
            'https://res.cloudinary.com/dyigive9u/image/upload/v1605780589/fklpmx1pbbafpspgn4vo.jpg',
            'https://res.cloudinary.com/dyigive9u/image/upload/v1605780620/larxbohavlepmx9le4yn.jpg');

        $full = $goats[array_rand($goats)];

        $parts = explode('upload/', $full);
        $medium = $parts[0].'upload/t_medium/'.$parts[1];
        $thumb = $parts[0].'upload/t_thumb/'.$parts[1];


        return [
            'public_id' => 'bof',
            'full' => $full,
            'medium' => $medium,
            'thumb' => $thumb,
            'imageable_id' => 0,
            'imageable_type' => ""
        ];
    }
}
