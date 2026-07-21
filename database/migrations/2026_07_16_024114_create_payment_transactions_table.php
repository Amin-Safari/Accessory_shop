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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('gateway')->default('zipal');
            $table->string('transaction_id')->nullable()->unique();
            $table->string('reference_id')->nullable()->unique();

            $table->decimal('amount');
            $table->string('currency')->default('IRR');

            $table->enum('status', [
                'initiated', 'pending', 'completed',
                'failed', 'cancelled', 'timed_out'
            ])->default('initiated');

            $table->json('gateway_request')->nullable();
            $table->json('gateway_response')->nullable();

            $table->timestamp('expires_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->text('error_message')->nullable();
            $table->integer('attempt_count')->default(1);

            $table->timestamps();

            $table->index('status');
            $table->index('order_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
