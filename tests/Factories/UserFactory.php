<?php

namespace PaulhenriL\EloquentBuilderDecorator\Tests\Factories;

use Faker\Generator;
use Illuminate\Database\Eloquent\Factories\Factory;
use PaulhenriL\EloquentBuilderDecorator\Tests\Fakes\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'type' => $this->faker->randomElement(['premium', 'regular']),
            'active' => $this->faker->boolean
        ];
    }

    protected function withFaker()
    {
        return \Faker\Factory::create();
    }
}
