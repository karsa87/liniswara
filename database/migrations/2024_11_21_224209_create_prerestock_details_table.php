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
        Schema::create('prerestock_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prerestock_id');
            $table->unsignedBigInteger('product_id');
            $table->tinyInteger('type');
            $table->integer('qty')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('prerestock_id');
            $table->foreign('prerestock_id')->references('id')->on('prerestocks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prerestock_details');
    }
};
