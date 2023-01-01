<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->mediumText('name')->nullable();
            $table->text("description")->nullable();
            $table->string("code",255)->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete("set null");
            $table->foreignId('manufacturer_id')->nullable()->constrained()->onDelete("set null");
            $table->foreignId('classification_id')->nullable()->constrained()->onDelete("set null");
            $table->foreignId("productgroup_id")->nullable()->constrained()->onDelete("set null");
            $table->foreignId("brand_id")->nullable()->constrained()->onDelete("set null");
            $table->decimal("selling_price",20,5)->nullable()->default(0);
            $table->decimal("cost_price",20,5)->nullable()->default(0);
            $table->text("barcode")->nullable();
            $table->string("location",255)->nullable();
            $table->boolean("expiry")->default("0");
            $table->integer("piece")->default(0);
            $table->integer("box")->default(0);
            $table->integer("cartoon")->default(0);
            $table->boolean("sachet")->default(0);
            $table->boolean("status")->default("1");
            $table->decimal("quantity")->default(0);
            $table->string("image")->nullable();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->unsignedBigInteger('last_updated_by')->nullable();
            $table->timestamps();

            $table->foreign("user_id")
                ->references("id")->on('users')->onDelete("set null");

            $table->foreign("last_updated_by")
                ->references("id")->on('users')->onDelete("set null");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
};
