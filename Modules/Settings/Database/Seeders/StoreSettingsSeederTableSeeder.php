<?php

namespace Modules\Settings\Database\Seeders;

use App\Classes\Settings;
use Illuminate\Database\Seeder;

class StoreSettingsSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Settings $settings)
    {
        $settings->put([
            "name" => "Store Name",
            "app_type" => "Bakery",
            "branch_name" => "",
            "vat" => NULL,
            "address_1" => NULL,
            "address_2" => NULL,
            "contact_number" => NULL,
            "logo" => "logo.png",
            "receipt_notes" => NULL,
            "branch_id" => 1
        ]);
    }
}
