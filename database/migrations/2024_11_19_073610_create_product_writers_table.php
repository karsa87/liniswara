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
        Schema::create('product_writers', function (Blueprint $table) {
            $table->bigInteger('product_id');
            $table->bigInteger('writer_id');

            $table->primary([
                'product_id',
                'writer_id',
            ], 'pk_product_writers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_writers');
    }
};
