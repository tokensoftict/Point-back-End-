<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Auth\Database\Seeders\UserSeederTableSeeder;
use Modules\BakeryManager\Database\Seeders\BakeryManagerDatabaseSeeder;
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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(StatusSeeder::class);
        $this->call(SettingsDatabaseSeeder::class);
        $this->call(BakeryManagerDatabaseSeeder::class);
        $this->call(UserSeederTableSeeder::class);
    }
}
