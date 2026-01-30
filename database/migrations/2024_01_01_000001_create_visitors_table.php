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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->enum('visitor_type', ['guest', 'broker', 'customer']);
            
            // Common fields
            $table->string('name');
            $table->string('mobile', 15);
            $table->string('email')->nullable();
            
            // Guest specific fields
            $table->enum('guest_type', ['vendor', 'contractor', 'family_member', 'interview', 'other'])->nullable();
            $table->string('company_name')->nullable();
            $table->string('whom_to_meet')->nullable(); // Employee name/ID
            $table->json('accompanying_persons')->nullable(); // Array of persons
            $table->integer('accompanying_count')->default(0);
            
            // Broker specific fields
            $table->string('broker_company')->nullable();
            $table->string('meet_department')->nullable(); // Sales, Management, Accounts
            
            // Customer specific fields
            $table->string('interested_project')->nullable();
            
            // OTP tracking
            $table->string('otp_code', 4)->nullable();
            $table->timestamp('otp_sent_at')->nullable();
            $table->boolean('otp_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            
            // Google Sheets sync
            $table->boolean('synced_to_sheets')->default(false);
            $table->timestamp('synced_at')->nullable();
            
            // Status and tracking
            $table->enum('status', ['pending', 'verified', 'checked_in', 'checked_out'])->default('pending');
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('checked_out_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('mobile');
            $table->index('visitor_type');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
