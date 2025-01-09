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
        Schema::create('inouts', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('order_id')->nullable();
            $table->bigInteger('stockin_id')->nullable();
            $table->bigInteger('item_id');
            $table->string('brand_name')->nullable();

            $table->string('type')->default('out');
            $table->string('market')->nullable();
            $table->string('cost')->default('0');
            $table->string('price')->default('0');

            $table->string('um')->default('ctn');
            $table->string('qty')->default('0');
            $table->string('qty_base')->default('0');
            $table->string('qty_ctn')->default('0');

            $table->string('discount')->default('0');
            $table->string('vat')->default('0');
            $table->string('amount')->default('0');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inouts');
    }
};
