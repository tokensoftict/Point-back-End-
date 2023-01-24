<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bakery_production_material_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId("bakeryproduction_id")->constrained()->cascadeOnDelete();
            $table->foreignId("rawmaterial_id")->constrained()->cascadeOnDelete();
            $table->foreignId("status_id")->constrained();
            $table->date("production_date");
            $table->time("production_time")->nullable();
            $table->decimal("quantity")->default(0.0);
            $table->decimal("cost_price",20,5)->default(0.0);
            $table->decimal("total",20,5)->default(0.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bakery_production_material_items');
    }
};
