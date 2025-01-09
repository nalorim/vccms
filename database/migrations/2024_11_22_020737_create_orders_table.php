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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('real_id')->unique()->nullable();
            $table->bigInteger('tax_id')->unique()->nullable();
            $table->string('order_id')->unique()->nullable();

            $table->bigInteger('customer_id');

            $table->string('phone')->nullable();
            $table->string('location')->nullable();

            $table->bigInteger('salesperson_id')->nullable();
            $table->bigInteger('market_id')->nullable();
            
            $table->string('invoice_type')->default('general');
            $table->string('order_type')->default('out');
            // out = purchase, in = credit note

            $table->string('order_date');
            $table->string('due_date');

            $table->string('vat')->default('0');
            $table->string('discount')->default('0');
            $table->string('terms')->default('7');
            $table->string('rate')->default('4100');

            $table->string('amount')->default('0');
            $table->string('remark')->nullable();

            $table->string('status')->default('pending');
            // pending: waiting for approval
            // void or cancel
            // complete

            $table->string('void_date')->nullable();
            $table->string('void_reason')->nullable();
            
            $table->bigInteger('created_by')->nullable();

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
