<?php

use Faker\Generator as Faker;

$factory->define(\App\Field::class, function (Faker $faker) {
    return [
        'field' => $faker->word
    ];
});
