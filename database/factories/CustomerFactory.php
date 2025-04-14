<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'document' => $this->faker->numerify('###########'),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->numerify('+55###########'),
            'zip_code' => $this->faker->numerify('########'),
            'street' => $this->faker->streetName(),
            'district' => Str::random(11),
            'city' => $this->faker->city(),
            'state' => $this->faker->state()
        ];
    }
}
