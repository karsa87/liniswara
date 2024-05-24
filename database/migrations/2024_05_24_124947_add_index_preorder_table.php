<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('preorders', function (Blueprint $table) {
            $table->index('collector_id');
            $table->index('branch_id');
            $table->index('customer_id');
            $table->index('customer_address_id');
            $table->index('area_id');
        });

        Schema::table('preorder_details', function (Blueprint $table) {
            $table->index('preorder_id');
            $table->index('product_id');
        });

        Schema::table('preorder_shippings', function (Blueprint $table) {
            $table->index('preorder_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index('collector_id');
            $table->index('branch_id');
            $table->index('customer_id');
            $table->index('customer_address_id');
            $table->index('area_id');
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->index('order_id');
            $table->index('product_id');
        });

        Schema::table('order_shippings', function (Blueprint $table) {
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
