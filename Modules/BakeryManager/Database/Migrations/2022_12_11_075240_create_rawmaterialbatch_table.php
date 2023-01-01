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
        Schema::create('rawmaterialbatch', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->date("received_date")->nullable();
            $table->date("expiry_date")->nullable();
            $table->decimal("quantity",20,5)->default(0);
            $table->unsignedBigInteger("supplier_id")->nullable();
            $table->foreignId("rawmaterial_id")->nullable()->constrained()->nullOnDelete();
            $table->foreign("supplier_id")->references("id")->on("supplier")->nullOnDelete();
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
        Schema::dropIfExists('rawmaterialbatch');
    }
};
