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
        Schema::table('products', function (Blueprint $table) {
            $table->double('price_zone_3', 18, 0, true)->default(0);
            $table->double('price_zone_4', 18, 0, true)->default(0);
            $table->double('price_zone_5', 18, 0, true)->default(0);
            $table->double('price_zone_6', 18, 0, true)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['price_zone_3', 'price_zone_4', 'price_zone_5', 'price_zone_6']);
        });
    }
};
