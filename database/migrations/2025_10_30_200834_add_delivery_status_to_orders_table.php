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
        Schema::table('orders', function (Blueprint $table) {
            // Add new delivery_status column
            $table->enum('delivery_status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending')->after('status');
            
            // Modify existing status column to only handle payment status
            $table->enum('status', ['pending', 'paid', 'cancelled', 'refunded'])->default('pending')->change();
        });
        
        // Migrate existing data
        DB::statement("UPDATE orders SET delivery_status = CASE 
            WHEN status IN ('shipped', 'delivered') THEN status
            WHEN status = 'cancelled' THEN 'cancelled'
            ELSE 'pending'
        END");
        
        DB::statement("UPDATE orders SET status = CASE 
            WHEN status IN ('shipped', 'delivered') THEN 'paid'
            ELSE status
        END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Restore original status enum
            $table->enum('status', ['pending', 'paid', 'shipped', 'delivered', 'cancelled', 'refunded'])->default('pending')->change();
            
            // Drop delivery_status column
            $table->dropColumn('delivery_status');
        });
    }
};