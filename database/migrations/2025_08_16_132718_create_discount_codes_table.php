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
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique()->comment('e.g., WELCOME10');
            $table->string('description', 255)->nullable();
            $table->string('type', 20)->comment('percent | fixed');
            $table->decimal('value', 10, 2)->comment('10% or 10.00');
            $table->decimal('min_order_total', 10, 2)->default(0.00);
            $table->integer('usage_limit')->nullable()->comment('How many times this code can be used in total');
            $table->integer('per_user_limit')->nullable()->comment('How many times one user can use it');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_codes');
    }
};
