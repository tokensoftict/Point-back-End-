<?php

namespace Modules\PaymentManager\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payment_method = [
            ['name'=>"CASH","status"=>1] ,
            ['name'=>"POS","status"=>1] ,
            ['name'=>"TRANSFER","status"=>1] ,
            ['name'=>"CREDIT","status"=>1],
        ];

        DB::table('payment_methods')->insert($payment_method);
    }
}
