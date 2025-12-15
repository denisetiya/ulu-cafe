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
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 15, 2);
            $table->string('bank_code', 20);
            $table->string('bank_name', 100);
            $table->string('account_number', 50);
            $table->string('account_name', 100);
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'processing', 'success', 'failed'])->default('pending');
            $table->string('iris_reference')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
