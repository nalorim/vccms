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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->string('image')->nullable();
            $table->string('name');

            $table->string('contact')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            $table->string('location')->nullable();
            $table->string('map')->nullable();

            $table->bigInteger('salesperson_id')->nullable();
            $table->bigInteger('market_id')->nullable();

            $table->string('vat')->default('0');
            $table->string('discount')->default('0');
            $table->string('terms')->default('1');

            $table->text('remark')->nullable();
            $table->boolean('status')->default(true);
            $table->bigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
