<?php

use Illuminate\Database\Seeder;

class DataSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $admins = [
            [
                'type'=>'su',
                'name' => 'Admin',
                'email' => 'admin@biketrack.com',
                'email_verified_at' => now(),
                'password' => bcrypt('admin123'), // password
                'remember_token' => Str::random(10),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        if(DB::table('admins')->get()->count() == 0)
        {
            DB::table('admins')->insert($admins);
        }
    }
}
