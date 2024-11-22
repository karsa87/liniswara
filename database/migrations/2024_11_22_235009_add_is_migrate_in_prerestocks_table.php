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
        Schema::table('prerestocks', function (Blueprint $table) {
            $table->boolean('is_migrate')->default(false);
            $table->bigInteger('restock_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prerestocks', function (Blueprint $table) {
            $table->dropColumn(['is_migrate', 'restock_id']);
        });
    }
};
