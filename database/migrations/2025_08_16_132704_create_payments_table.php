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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('provider', 50)->default('gateway'); // set to your chosen gateway
            $table->string('provider_txn_id', 191)->unique()->nullable()->comment('Gateway charge/transaction id');
            $table->string('session_id', 191)->nullable()->comment('Checkout session / intent id');
            $table->string('webhook_last_id', 191)->nullable()->comment('Last processed webhook event id');
            $table->timestamp('webhook_received_at')->nullable();
            $table->json('raw_snapshot')->nullable()->comment('Gateway payload snapshot');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('JOD');
            $table->enum('status', ['initiated', 'succeeded', 'failed', 'refunded'])->default('initiated');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            // Foreign key
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('restrict');

            // Indexes
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
