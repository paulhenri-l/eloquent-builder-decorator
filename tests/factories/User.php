<?php

use Illuminate\Database\Eloquent\Factory;
use Faker\Generator as Faker;
use PaulhenriL\EloquentBuilderDecorator\Tests\Fakes\User;

/** @var Factory $factory */
$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'type' => $faker->randomElement(['premium', 'regular']),
        'active' => $faker->boolean
    ];
});
