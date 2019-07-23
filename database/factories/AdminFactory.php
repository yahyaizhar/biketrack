<?php

use Faker\Generator as Faker;
use App\Model\Admin\Admin;

$factory->define(Admin::class, function (Faker $faker) {
    static $password;
    return [
        //
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => $password ?: $password = bcrypt('admin123'), // password
        'remember_token' => Str::random(10),
    ];
});
