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
        Schema::connection('mysql_log')->create('imports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('file_id');
            $table->integer('total_failed')->default(0);
            $table->integer('total_success')->default(0);
            $table->integer('total_record')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->bigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_log')->dropIfExists('imports');
    }
};
