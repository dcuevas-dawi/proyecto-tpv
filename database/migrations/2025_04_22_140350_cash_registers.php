<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    // This migration creates the 'cash_registers' table, which stores information about cash registers associated with a user (stablishment).
    public function up(): void
    {
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('opening_employee_id')->constrained('employees');
            $table->foreignId('closing_employee_id')->nullable()->constrained('employees');
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            $table->decimal('opening_amount', 10, 2);
            $table->decimal('real_closing_amount', 10, 2)->nullable();
            $table->decimal('theoretical_closing_amount', 10, 2)->nullable();
            $table->decimal('difference', 10, 2)->nullable();
            $table->text('comments')->nullable();
            $table->string('status')->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration drops the 'cash_registers' table and removes the foreign key constraint from the 'orders' table.
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['cash_register_id']);
            // Opcional: mantener la columna pero sin restricción
            $table->foreignId('cash_register_id')->nullable()->change();
        });

        Schema::dropIfExists('cash_registers');
    }
};
