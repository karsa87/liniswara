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
            $table->string('label')->nullable();

            $table->dropColumn(['is_migrate', 'restock_id']);
        });

        Schema::table('prerestock_details', function (Blueprint $table) {
            $table->integer('qty_migrate')->default(0);
        });

        Schema::table('restocks', function (Blueprint $table) {
            $table->bigInteger('prerestock_id')->nullable();
        });

        Schema::table('restock_details', function (Blueprint $table) {
            $table->bigInteger('prerestock_detail_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prerestocks', function (Blueprint $table) {
            $table->dropColumn('label');
            $table->boolean('is_migrate')->default(false);
            $table->bigInteger('restock_id');
        });

        Schema::table('prerestock_details', function (Blueprint $table) {
            $table->dropColumn('qty_migrate');
        });

        Schema::table('restocks', function (Blueprint $table) {
            $table->dropColumn('prerestock_id');
        });

        Schema::table('restock_details', function (Blueprint $table) {
            $table->dropColumn('prerestock_detail_id');
        });
    }
};
