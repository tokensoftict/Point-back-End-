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
        Schema::create('bakeryproductionlogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("bakeryproduction_id")->constrained()->cascadeOnDelete();
            $table->foreignid("stock_id")->constrained()->cascadeOnDelete();
            $table->decimal("quantity")->default(0.0);
            $table->decimal("rough")->default(0.0);
            $table->decimal("bags")->default(0.0);
            $table->foreignId("user_id")->constrained();
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
        Schema::dropIfExists('bakeryproductionlogs');
    }
};
