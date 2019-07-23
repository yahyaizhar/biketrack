<?php

use Faker\Generator as Faker;
use App\Model\Client\Client_Rider;
use App\Model\Client\Client;
use App\Model\Rider\Rider;
use App\Model\Rider\Rider_Location;
use App\Model\Rider\Rider_Message;
use App\Model\Admin\Admin;

$factory->define(Client_Rider::class, function (Faker $faker) {
    return [
        'client_id' => function(){
			return Client::all()->random();
		},
        'rider_id' => function(){
			return Rider::all()->random();
        },
    ];
});
$factory->define(Rider_Location::class, function (Faker $faker) {
    return [
        'rider_id' => function(){
			return Rider::all()->random();
        },
        'latitude' => $faker->latitude($min = 30, $max = 35),
        'longitude' => $faker->longitude($min = 70, $max = 75),
    ];
});
$factory->define(Rider_Message::class, function (Faker $faker) {
    return [
        'admin_id' => function(){
			return Admin::all()->random();
        },
        'rider_id' => function(){
			return Rider::all()->random();
        },
        'message' => $faker->realText($maxNbChars = 200, $indexSize = 2),
    ];
});