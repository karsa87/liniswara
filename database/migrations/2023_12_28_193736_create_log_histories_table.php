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
        Schema::create('log_histories', function (Blueprint $table) {
            $table->id();
            $table->dateTime('log_datetime');
            $table->bigInteger('user_id');
            $table->text('information');
            $table->string('table', 255);
            $table->bigInteger('record_id');
            $table->string('record_type', 255);
            $table->tinyInteger('transaction_type');
            $table->text('data_before')->nullable();
            $table->text('data_after')->nullable();
            $table->text('data_change')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_histories');
    }
};
