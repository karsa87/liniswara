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
        Schema::create('preorder_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('preorder_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('qty');
            $table->double('price', 18, 0, true);
            $table->string('discount_description')->nullable();
            $table->double('discount', 18, 0, true)->default(0);
            $table->double('total_price', 18, 0, true);
            $table->double('total_discount', 18, 0, true)->default(0);
            $table->double('total', 18, 0, true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preorder_details');
    }
};
