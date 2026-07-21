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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();

            $table->integer('quantity');
            $table->integer('unit_price');
            $table->integer('discount')->default(0);
            $table->integer('discount_amount')->default(0);
            $table->integer('total_price');

            $table->integer('reserved_quantity')->default(0);
            $table->timestamp('reserved_at')->nullable();
            $table->timestamp('reserved_until')->nullable();

            $table->timestamps();

            $table->index('product_id');
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
