<?php

use Faker\Generator as Faker;

$factory->define(\App\Person::class, function (Faker $faker) {
    return [
        'name'=>$faker->name,
        'description'=>$faker->text,
        'image_url' => $faker->imageUrl(),
        'achievements' => [$faker->words(5, true), $faker->words(5, true)],
        'books' => [['url'=>$faker->url, 'title'=>$faker->words(3, true)]],
        'wiki' => $faker->url,
        'birth_country' => array_rand(\App\Countries::ALL),
        'gender' => array_rand(array_flip(['m','f'])),
        'born' => $faker->date(),
    ];
});
