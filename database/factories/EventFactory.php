<?php

namespace Database\Factories;

use DateTime;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $duration = rand(0,3);
        $start = $this->faker->dateTimeBetween('-30 days', '+30 days')->format("Y-m-d H:i:s");
        $startDate = new DateTime($start);
        $end = $startDate->modify('+ '.$duration.' days')->format("Y-m-d H:i:s");

        return [
            'type' => 'project',
            'start' => $start,
            'end' => $end,
            'timed' => $this->faker->boolean(),
            'child_id' => 0,
            'child_type' => ''
        ];
    }
}
