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
        Schema::create('payment_webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id');
            $table->string('event_id', 191)->nullable();
            $table->string('event_type', 80)->nullable();
            $table->boolean('signature_ok')->default(false);
            $table->json('payload')->nullable();
            $table->timestamp('received_at')->useCurrent();

            // Foreign key
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');

            // Indexes
            $table->index('payment_id');
            $table->index('event_id');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_webhook_logs');
    }
};
