<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Discussion;
use Faker\Generator as Faker;

$factory->define(Discussion::class, function (Faker $faker) {
    return [
        'topic' => $faker->sentence(6, true),
        'description' => $faker->text(200),
    ];
});
