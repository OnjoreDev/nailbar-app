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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Client Info (No user_id required)
            $table->string('client_name');
            $table->string('client_phone');
            $table->string('client_email')->nullable();

            // Service Choice & Merchant Link
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('merchant_id')->constrained()->cascadeOnDelete(); // Removed ->after('id')

            // Booking & Payment Info
            $table->date('booking_date');
            $table->decimal('deposit_amount', 8, 2)->default(500.00);
            $table->enum('payment_method', ['cash', 'mpesa']);
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('mpesa_receipt_number')->nullable();
            $table->string('merchant_request_id')->nullable(); // For tracking STK Push
            $table->string('checkout_request_id')->nullable(); // For tracking STK Push
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Fast index to check daily capacity (max 10 limit)
            $table->index('booking_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
