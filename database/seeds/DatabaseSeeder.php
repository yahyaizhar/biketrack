<?php

use Illuminate\Database\Seeder;
use App\Model\Admin\Admin;
use App\Model\Client\Client;
use App\Model\Rider\Rider;
use App\Model\Client\Client_Rider;
use App\Model\Rider\Rider_Location;
use App\Model\Rider\Rider_Message;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call([
            DataSeed::class,
        ]);
        // factory(Admin::class,2)->create();
        // factory(Client::class,10)->create();
        // factory(Rider::class,20)->create();
        // factory(Client_Rider::class,20)->create();
        // factory(Rider_Location::class,20)->create();
        // factory(Rider_Message::class,20)->create();
    }
}
