<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Auth\Entities\User;
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bakeryproductions', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->date("production_date");
            $table->time("production_time")->nullable();
            $table->foreignId("status_id")->constrained();
            $table->text("remark")->nullable();
            $table->foreignId("user_id")->constrained();
            $table->unsignedBigInteger("completed_id")->nullable();
            $table->timestamps();
            $table->foreign("completed_by")->references("users")->on("id")->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bakeryproductions');
    }
};
