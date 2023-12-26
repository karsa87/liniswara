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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('preorder_id');
            $table->date('date');
            $table->string('invoice_number');
            $table->unsignedBigInteger('collector_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('customer_address_id');
            $table->tinyInteger('status');
            $table->tinyInteger('status_payment');
            $table->tinyInteger('method_payment');
            $table->string('marketing')->nullable();
            $table->text('notes')->nullable();
            $table->text('notes_staff')->nullable();
            $table->tinyInteger('zone')->nullable();
            $table->tinyInteger('tax')->nullable();
            $table->double('total_amount_details', 18, 0, true)->default(0);
            $table->double('total_discount_details', 18, 0, true)->default(0);
            $table->double('subtotal', 18, 0, true)->default(0);
            $table->double('shipping_price', 18, 0, true)->default(0);
            $table->tinyInteger('discount_type')->default(0);
            $table->double('discount_percentage', 18, 0, true)->default(0);
            $table->double('discount_price', 18, 0, true)->default(0);
            $table->double('tax_amount', 18, 0, true)->default(0);
            $table->double('total_amount', 18, 0, true)->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
