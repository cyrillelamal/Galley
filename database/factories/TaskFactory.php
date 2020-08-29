<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'body' => $faker->text(127),
        'expires_at' => (bool)mt_rand(0, 1) ? $faker->dateTime : null,
    ];
});
