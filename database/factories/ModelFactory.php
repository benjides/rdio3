<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Podcast::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->name,
        'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
        'link' => $faker->url,
        'duration' => $faker->randomDigitNotNull ,
        'date' => $faker->unixTime($max = 'now'),
    ];
});
