<?php

namespace Modules\Auth\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Auth\Entities\User;

class UserSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        //User::factory()->count(5)->create();
        DB::table('users')->insert([
            [
                'name' => 'Administrator',
                'email' => 'admin@admin.com',
                'username'=>'admin',
                'usergroup_id'=>1,
                'email_verified_at'=>Carbon::now(),
                'password' => bcrypt('123456'),
                'status' => '1',
                'phone' =>'08090999897',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);

    }
}
