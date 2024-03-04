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
        Schema::create('customer_area', function (Blueprint $table) {
            $table->bigInteger('customer_id');
            $table->bigInteger('area_id');

            $table->primary([
                'customer_id',
                'area_id',
            ], 'pk_customer_area');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_area');
    }
};
