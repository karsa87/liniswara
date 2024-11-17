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
        Schema::create('product_schools', function (Blueprint $table) {
            $table->bigInteger('product_id');
            $table->bigInteger('school_id');

            $table->primary([
                'product_id',
                'school_id',
            ], 'pk_product_schools');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_schools');
    }
};
