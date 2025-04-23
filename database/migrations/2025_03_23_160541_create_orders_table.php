<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    // This migration creates the 'orders' table, which stores information about orders placed by users.
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('table_id')->constrained()->onDelete('cascade');
            $table->string('status');
            $table->decimal('total_price', 8, 2)->default(0);
            $table->timestamp('closed_at')->nullable();
            $table->foreignId('employee_id')->nullable()->after('table_id')
                ->constrained('employees')->nullOnDelete(); // Employee who closed the order
            $table->foreignId('cash_register_id')->nullable()->after('employee_id')
                ->constrained()->nullOnDelete(); // Cash register where the order was opened
            $table->timestamps();
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
