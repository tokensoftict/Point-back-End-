<?php

namespace Modules\BakeryManager\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MaterialTypeSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $materials = array("FLOUR", "SUGAR", "BUTTER", "SALT", "YEAST", "IMPROVER", "GROUNDNUT OIL", "PRESERVATIVE", "HONEY", "FLAVOUR", "DEASEL", "OTHERS");

        $_insert = [];
        foreach ($materials as  $material) {
            $_insert[] = [ 'name' => $material, 'status' => '1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()];
        }

        DB::table('materialtypes')->insert($_insert);
    }
}
