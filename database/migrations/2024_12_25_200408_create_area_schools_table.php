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
        Schema::create('area_schools', function (Blueprint $table) {
            $table->bigInteger('area_id');
            $table->bigInteger('school_id');
            $table->double('target', 18, 0, true)->default(0);

            $table->primary(['area_id', 'school_id'], 'pk_area_schools');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('area_schools');
    }
};
