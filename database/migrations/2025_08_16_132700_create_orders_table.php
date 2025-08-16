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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['pending', 'paid', 'shipped', 'delivered', 'cancelled', 'refunded'])->default('pending');
            $table->enum('payment_method', ['cod', 'card'])->default('cod');
            $table->decimal('total_amount', 10, 2)->default(0.00);

            // Delivery destination (can be receiver's address)
            $table->text('shipping_address')->nullable();
            $table->unsignedBigInteger('city_id');

            // Gift / receiver info
            $table->boolean('is_gift')->default(false);
            $table->string('receiver_name', 150)->nullable();
            $table->string('receiver_phone', 40)->nullable();
            $table->text('location_description')->nullable()->comment('Landmarks / directions');
            $table->text('extra_notes')->nullable()->comment('Any extra notes from buyer');
            $table->boolean('is_anonymous_delivery')->default(false);

            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('restrict');

            // Indexes
            $table->index(['user_id', 'created_at']);
            $table->index('status');
            $table->index('city_id');
            $table->index('is_gift');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
