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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('code');
            $table->text('description')->nullable();
            $table->double('stock', 12)->default(0);
            $table->double('price', 18)->default(0);
            $table->tinyInteger('discount_type')->nullable();
            $table->double('discount_price', 18)->nullable();
            $table->double('discount_percentage', 18)->nullable();
            $table->boolean('is_best_seller')->default(false);
            $table->boolean('is_recommendation')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_special_discount')->default(false);
            $table->boolean('is_active')->default(false);
            $table->unsignedBigInteger('thumbnail_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->fullText('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
