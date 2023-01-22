<?php

namespace Modules\Auth\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserGroupSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $groups = [
            'System Administrator'
        ];

        $_insert = [];
        foreach ($groups as $group) {
            $_insert[] = ['name' => $group, 'status' => '1','created_at' => Carbon::now(), 'updated_at' => Carbon::now()];
        }

        DB::table('usergroups')->insert($_insert);
    }
}
