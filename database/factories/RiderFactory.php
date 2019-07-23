<?php

use Faker\Generator as Faker;
use App\Model\Rider\Rider;

$factory->define(Rider::class, function (Faker $faker) {
    static $password;
    return [
        //
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->unique()->e164PhoneNumber,
        'address' => $faker->city,
        'vehicle_number' => $faker->numerify('PAK ###'),
        'password' => $password ?: $password = bcrypt('admin123'), // password
        'status' => $faker->boolean($chanceOfGettingTrue = 90),
        'email_verified_at' => now(),
        'remember_token' => Str::random(10),
    ];
});
