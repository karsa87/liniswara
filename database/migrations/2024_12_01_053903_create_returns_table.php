<?php

use App\Enums\ReturnOrder\StatusEnum;
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
        Schema::create('return_orders', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->string('no_return');
            $table->text('notes')->nullable();
            $table->bigInteger('order_id');
            $table->bigInteger('branch_id');

            $table->tinyInteger('tax')->nullable();
            $table->double('total_amount_details', 18, 0, true)->default(0);
            $table->double('total_discount_details', 18, 0, true)->default(0);
            $table->double('subtotal', 18, 0, true)->default(0);
            $table->double('shipping_price', 18, 0, true)->default(0);
            $table->tinyInteger('discount_type')->default(0);
            $table->double('discount_percentage', 18, 0, true)->default(0);
            $table->double('discount_price', 18, 0, true)->default(0);
            $table->double('tax_amount', 18, 0, true)->default(0);
            $table->double('total_amount', 18, 0, true)->default(0);
            $table->enum('status', [
                StatusEnum::NEW()->value,
                StatusEnum::CONFIRMATION()->value,
                StatusEnum::CANCELLED()->value,
            ])->default(StatusEnum::NEW()->value);

            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_orders');
    }
};
