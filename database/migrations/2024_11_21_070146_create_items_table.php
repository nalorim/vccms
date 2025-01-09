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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('brand_id');
            $table->string('color')->nullable();

            $table->integer('factor')->default(1);
            $table->string('um')->default('box');
            $table->string('description')->nullable();

            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->string('price')->default('0');

            $table->string('remark')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->boolean('status')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
