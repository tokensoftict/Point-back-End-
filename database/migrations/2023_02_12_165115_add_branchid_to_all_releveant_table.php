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

        Schema::table('bakeryproductions', function (Blueprint $table) {
            $table->foreignId("branch_id")->after("name")->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('bakery_production_material_items', function (Blueprint $table) {
            $table->foreignId("branch_id")->after("rawmaterial_id")->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('bakery_production_products_items', function (Blueprint $table) {
            $table->foreignId("branch_id")->after("bakeryproduction_id")->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('credit_payment_logs', function (Blueprint $table) {
            $table->foreignId("branch_id")->after("payment_method_id")->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('customer_deposits_history', function (Blueprint $table) {
            $table->foreignId("branch_id")->after("customer_id")->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId("branch_id")->after("customer_id")->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->foreignId("branch_id")->after("customer_id")->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('invoice_item_batches', function (Blueprint $table) {
            $table->foreignId("branch_id")->after("invoice_item_id")->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId("branch_id")->after("customer_id")->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('payment_method_table', function (Blueprint $table) {
            $table->foreignId("branch_id")->after("customer_id")->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->foreignId("branch_id")->after("supplier_id")->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->foreignId("branch_id")->after("purchase_order_id")->nullable()->constrained()->cascadeOnDelete();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::disableForeignKeyConstraints();
        Schema::table('bakeryproductions', function (Blueprint $table) {
            $table->dropConstrainedForeignId("branch_id");
        });

        Schema::table('bakery_production_material_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId("branch_id");
        });

        Schema::table('bakery_production_products_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId("branch_id");
        });

        Schema::table('credit_payment_logs', function (Blueprint $table) {
            $table->dropConstrainedForeignId("branch_id");
        });

        Schema::table('customer_deposits_history', function (Blueprint $table) {
            $table->dropConstrainedForeignId("branch_id");
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropConstrainedForeignId("branch_id");
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId("branch_id");
        });

        Schema::table('invoice_item_batches', function (Blueprint $table) {
            $table->dropConstrainedForeignId("branch_id");
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId("branch_id");
        });

        Schema::table('payment_method_table', function (Blueprint $table) {
            $table->dropConstrainedForeignId("branch_id");
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId("branch_id");
        });

        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId("branch_id");
        });
        Schema::enableForeignKeyConstraints();

    }
};
