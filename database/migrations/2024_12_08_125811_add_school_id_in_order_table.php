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
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('school_id')->nullable();
        });

        Schema::table('preorders', function (Blueprint $table) {
            $table->integer('school_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('school_id');
        });

        Schema::table('preorders', function (Blueprint $table) {
            $table->dropColumn('school_id');
        });
    }
};