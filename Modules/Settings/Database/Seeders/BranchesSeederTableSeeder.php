<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BranchesSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table("branches")->insert([
            [
                "quantity_column" => "quantity",
                "name" => "Main Branch",
                "address_1" => "",
                "address_2" => "",
                "phone" => ""
            ]
        ]);

        DB::table("bakeryproductions")->update(["branch_id"=>1]);
        DB::table("bakery_production_material_items")->update(["branch_id"=>1]);
        DB::table("bakery_production_products_items")->update(["branch_id"=>1]);
        DB::table("credit_payment_logs")->update(["branch_id"=>1]);
        DB::table("customer_deposits_history")->update(["branch_id"=>1]);
        DB::table("invoices")->update(["branch_id"=>1]);
        DB::table("invoice_items")->update(["branch_id"=>1]);
        DB::table("invoice_item_batches")->update(["branch_id"=>1]);
        DB::table("payments")->update(["branch_id"=>1]);
        DB::table("payment_method_table")->update(["branch_id"=>1]);
        DB::table("purchase_orders")->update(["branch_id"=>1]);
        DB::table("purchase_order_items")->update(["branch_id"=>1]);
    }
}
