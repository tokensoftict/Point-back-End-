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
        Schema::create('bakery_production_products_items', function (Blueprint $table) {
            $table->id();
            $table->foreignid("stock_id")->constrained()->cascadeOnDelete();
            $table->foreignId("bakeryproduction_id")->constrained()->cascadeOnDelete();
            $table->foreignId("status_id")->constrained();
            $table->date("production_date");
            $table->time("production_time")->nullable();
            $table->decimal("quantity")->default(0.0);
            $table->decimal("selling_price")->default(0.0);
            $table->decimal("total")->default(0.0);
            $table->decimal("recycle")->default(0.0);
            $table->decimal("roughs")->default(0.0);

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
        Schema::dropIfExists('bakery_production_products_items');
    }
};
