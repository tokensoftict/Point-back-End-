<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Auth\Database\Seeders\UserGroupSeederTableSeeder;
use Modules\Auth\Database\Seeders\UserSeederTableSeeder;
use Modules\BakeryManager\Database\Seeders\BakeryManagerDatabaseSeeder;
use Modules\CustomerManager\Database\Seeders\CustomerSeederTableSeeder;
use Modules\PaymentManager\Database\Seeders\PaymentMethodSeederTableSeeder;
use Modules\Settings\Database\Seeders\SettingsDatabaseSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(StatusSeeder::class);
        $this->call(SettingsDatabaseSeeder::class);
        $this->call(BakeryManagerDatabaseSeeder::class);
        $this->call(CustomerSeederTableSeeder::class);
        $this->call(PaymentMethodSeederTableSeeder::class);
        $this->call(UserGroupSeederTableSeeder::class);
        $this->call(UserSeederTableSeeder::class);
    }
}
