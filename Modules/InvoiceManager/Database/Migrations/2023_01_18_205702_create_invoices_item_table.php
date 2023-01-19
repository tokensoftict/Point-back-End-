<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId("invoice_id")->constrained()->cascadeOnDelete();
            $table->foreignId("stock_id")->nullable()->constrained()->nullOnDelete();
            $table->bigInteger("quantity");
            $table->foreignId("customer_id")->nullable()->constrained()->nullOnDelete();
            $table->foreignId("status_id")->default(1)->constrained(); //["PAID","DRAFT","DISCOUNT","VOID","HOLD","COMPLETE"]
            $table->unsignedBigInteger("added_by");
            $table->date("invoice_date");
            $table->time("sales_time");
            $table->decimal("cost_price",20,5)->nullable();
            $table->decimal("selling_price",20,5)->nullable();
            $table->decimal("profit",20,5)->nullable();
            $table->decimal("total_cost_price",20,5)->nullable();
            $table->decimal("total_selling_price",20,5)->nullable();
            $table->decimal("total_profit",20,5)->nullable();
            $table->string("discount_type")->nullable(); //['Fixed','Percentage','None']
            $table->decimal("discount_amount",20,5)->nullable();
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
        Schema::dropIfExists('invoice_items');
    }
}
