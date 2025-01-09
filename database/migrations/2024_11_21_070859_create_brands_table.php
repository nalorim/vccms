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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('factor')->default(1);
            $table->string('um')->default('ctn');
            $table->string('base_price')->default('0');
            $table->string('ctn_price')->default('0');
            $table->string('description')->nullable();
            $table->bigInteger('vendor_id')->nullable();
            $table->string('image')->nullable();
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('brands');
    }
};
