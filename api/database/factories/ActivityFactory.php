<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Activity;
use Faker\Generator as Faker;

$factory->define(Activity::class, function (Faker $faker) {
    return [
        'activity' => $faker->text(30),
        'status_id' => function () {
            return \App\Models\Status::all()->random()->id;
        },
        'deadline' => $faker->date(),
    ];
});
