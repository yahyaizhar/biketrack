<?php

use Faker\Generator as Faker;
use App\Model\Client\Client;

$factory->define(Client::class, function (Faker $faker) {
    static $password;
    return [
        //
        'name' => $faker->company,
        'email' => $faker->unique()->companyEmail,
        'phone' => $faker->unique()->e164PhoneNumber,
        'about' => $faker->realText($maxNbChars = 200, $indexSize = 2),
        'address' => $faker->city,
        'latitude' => $faker->latitude($min = 30, $max = 35),
        'longitude' => $faker->longitude($min = 70, $max = 75),
        'password' => $password ?: $password = bcrypt('admin123'), // password
        'status' => $faker->boolean($chanceOfGettingTrue = 90),
        'email_verified_at' => now(),
        'remember_token' => Str::random(10),
    ];
});
