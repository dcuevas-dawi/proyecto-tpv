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
        Schema::dropIfExists('cash_registers');
    }
};
