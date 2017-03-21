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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'id'             => 1,
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Product::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});

$factory->define(App\ProductRetailer::class, function (Faker\Generator $faker) {
    return [
        'price' => mt_rand(1000, 9999),
        'url'   => $faker->url,
        'retailer_id' => function () {
            return factory(App\Retailer::class)->create()->id;
        }
    ];
});

$factory->define(App\Retailer::class, function (Faker\Generator $faker) {
    return [
        'name'          => $faker->company,
        'retailer_code' => $faker->randomLetter
    ];
});