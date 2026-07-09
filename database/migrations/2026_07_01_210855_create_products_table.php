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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id');
            $table->string('name');
            $table->json('images');
            $table->string('description');
            $table->string('slug');
            $table->decimal('price');
            $table->decimal('discount');
            $table->decimal('total');
            $table->boolean('is_new');
            $table->boolean('is_active');
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('sold_count')->default(0);
            $table->timestamps();


            $table->index(['category_id', 'is_active']);
            $table->index('price');
            $table->index('sold_count');
            $table->index('views');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
