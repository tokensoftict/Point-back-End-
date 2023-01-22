<?php

namespace Modules\CustomerManager\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('customers')->insert([
            'firstname'=>'Generic',
            'lastname'=> 'Customer',
        ]);
    }
}
