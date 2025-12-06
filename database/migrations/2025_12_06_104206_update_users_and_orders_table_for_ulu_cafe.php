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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('customer'); // customer, cashier, owner
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('table_number')->nullable()->after('customer_name');
            $table->string('customer_address')->nullable()->change(); // Not required for dine-in
            $table->string('customer_phone')->nullable()->change();
            $table->string('payment_status')->default('unpaid')->after('status'); // unpaid, paid, failed, expired
            $table->string('snap_token')->nullable()->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('table_number');
            $table->text('customer_address')->nullable(false)->change();
            $table->string('customer_phone')->nullable(false)->change();
            $table->dropColumn('payment_status');
            $table->dropColumn('snap_token');
        });
    }
};
